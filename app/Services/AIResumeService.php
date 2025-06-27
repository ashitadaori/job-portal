<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\JobSeekerProfile;
use App\Models\Job;

class AIResumeService
{
    /**
     * Generate an optimized resume based on user profile and target job
     */
    public function generateResume(JobSeekerProfile $profile, ?Job $targetJob = null)
    {
        try {
            $prompt = $this->buildResumePrompt($profile, $targetJob);
            
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert resume writer and career counselor. Create a professional resume that highlights relevant skills and experience.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);

            return $result->choices[0]->message->content;
        } catch (\Exception $e) {
            \Log::error('Error generating resume: ' . $e->getMessage());
            throw new \Exception('Failed to generate resume. Please try again later.');
        }
    }

    /**
     * Analyze resume and provide optimization suggestions
     */
    public function analyzeResume(string $resumeText)
    {
        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert resume reviewer. Analyze the resume and provide specific suggestions for improvement.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please analyze this resume and provide specific suggestions for improvement: \n\n" . $resumeText
                    ]
                ]
            ]);

            return $result->choices[0]->message->content;
        } catch (\Exception $e) {
            \Log::error('Error analyzing resume: ' . $e->getMessage());
            throw new \Exception('Failed to analyze resume. Please try again later.');
        }
    }

    private function buildResumePrompt(JobSeekerProfile $profile, ?Job $targetJob)
    {
        $prompt = "Create a professional resume for a candidate with the following background:\n\n";
        $prompt .= "Skills: " . implode(", ", $profile->skills ?? []) . "\n";
        $prompt .= "Experience: " . ($profile->experience ?? 'No experience provided') . "\n";
        $prompt .= "Education: " . implode(", ", $profile->education ?? []) . "\n";
        
        if ($targetJob) {
            $prompt .= "\nTarget Job Details:\n";
            $prompt .= "Title: " . $targetJob->title . "\n";
            $prompt .= "Requirements: " . $targetJob->requirements . "\n";
        }
        
        return $prompt;
    }
} 