<?php

namespace App\Http\Controllers;

use App\Services\AIResumeService;
use App\Services\AIJobMatchingService;
use App\Models\Job;
use App\Models\JobSeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\JobType;
use OpenAI\OpenAI;

class AIFeaturesController extends Controller
{
    protected $resumeService;
    protected $matchingService;

    public function __construct(AIResumeService $resumeService, AIJobMatchingService $matchingService)
    {
        $this->resumeService = $resumeService;
        $this->matchingService = $matchingService;
    }

    /**
     * Generate or optimize resume
     */
    public function generateResume(Request $request)
    {
        try {
            $profile = Auth::user()->jobSeekerProfile;
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job seeker profile not found. Please complete your profile first.'
                ], 404);
            }

            if (!$profile->skills || empty($profile->skills)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please add your skills to your profile before generating a resume.'
                ], 400);
            }

            $targetJob = null;
            
            if ($request->has('job_id')) {
                $targetJob = Job::findOrFail($request->job_id);
            }

            $resume = $this->resumeService->generateResume($profile, $targetJob);

            return response()->json([
                'success' => true,
                'resume' => $resume
            ]);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error connecting to AI service. Please try again later.'
            ], 503);
        } catch (\Exception $e) {
            \Log::error('Resume Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating your resume. Please try again.'
            ], 500);
        }
    }

    /**
     * Analyze existing resume
     */
    public function analyzeResume(Request $request)
    {
        try {
            $request->validate([
                'resume_text' => 'required|string|min:50'
            ], [
                'resume_text.required' => 'Please provide the resume text to analyze.',
                'resume_text.min' => 'Resume text is too short. Please provide more content for better analysis.'
            ]);

            $analysis = $this->resumeService->analyzeResume($request->resume_text);

            return response()->json([
                'success' => true,
                'analysis' => $analysis
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error connecting to AI service. Please try again later.'
            ], 503);
        } catch (\Exception $e) {
            \Log::error('Resume Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while analyzing your resume. Please try again.'
            ], 500);
        }
    }

    /**
     * Get job match score and analysis
     */
    public function getJobMatch(Request $request)
    {
        try {
            $request->validate([
                'job_id' => 'required|exists:jobs,id'
            ]);

            $job = Job::findOrFail($request->job_id);
            $profile = Auth::user()->jobSeekerProfile;

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete your profile before using the job match feature.'
                ], 404);
            }

            if (!$profile->skills || empty($profile->skills)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please add your skills to your profile before using the job match feature.'
                ], 400);
            }

            // Calculate match score and analyze skill gaps
            $analysis = $this->calculateJobMatch($profile, $job);

            return response()->json([
                'success' => true,
                'match_score' => $analysis['match_score'],
                'skill_gap_analysis' => $analysis['skill_gap_analysis'],
                'missing_skills' => $analysis['missing_skills'],
                'recommendations' => $analysis['recommendations']
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Match Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while calculating job match. Please try again.'
            ], 500);
        }
    }

    private function calculateJobMatch($profile, $job)
    {
        try {
            $prompt = "Analyze the job seeker's profile against the job requirements and provide:\n" .
                     "1. A match score (percentage)\n" .
                     "2. Detailed skill gap analysis\n" .
                     "3. List of missing skills\n" .
                     "4. Specific recommendations for improvement\n\n" .
                     "Job Seeker Profile:\n" .
                     "Skills: " . implode(", ", $profile->skills) . "\n" .
                     "Experience: " . ($profile->experience ?? 'None') . "\n" .
                     "Education: " . implode(", ", $profile->education ?? []) . "\n\n" .
                     "Job Requirements:\n" .
                     "Title: " . $job->title . "\n" .
                     "Description: " . $job->description . "\n" .
                     "Requirements: " . $job->requirements . "\n";

            $result = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert job match analyzer. Provide detailed analysis of job fit.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            if (!isset($result->choices[0]->message->content)) {
                throw new \Exception('Failed to generate job match analysis.');
            }

            $analysis = $result->choices[0]->message->content;
            
            // Parse the AI response
            preg_match('/(\d+)%/', $analysis, $matches);
            $matchScore = isset($matches[1]) ? (int)$matches[1] : 0;
            
            preg_match('/Missing Skills:(.*?)(?=\n\n|$)/s', $analysis, $matches);
            $missingSkills = isset($matches[1]) ? 
                array_map('trim', explode(',', trim($matches[1]))) : 
                [];
            
            preg_match('/Skill Gap Analysis:(.*?)(?=Missing Skills:|$)/s', $analysis, $matches);
            $skillGapAnalysis = isset($matches[1]) ? trim($matches[1]) : '';
            
            preg_match('/Recommendations:(.*?)$/s', $analysis, $matches);
            $recommendations = isset($matches[1]) ? trim($matches[1]) : '';

            return [
                'match_score' => $matchScore,
                'skill_gap_analysis' => $skillGapAnalysis,
                'missing_skills' => $missingSkills,
                'recommendations' => $recommendations
            ];
        } catch (\Exception $e) {
            \Log::error('Job Match Calculation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get match scores for multiple jobs
     */
    public function getJobMatchScores(Request $request)
    {
        $request->validate([
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:jobs,id'
        ]);

        $profile = Auth::user()->jobSeekerProfile;
        $scores = [];

        foreach ($request->job_ids as $jobId) {
            $job = Job::find($jobId);
            $scores[$jobId] = $this->matchingService->calculateMatchScore($profile, $job);
        }

        return response()->json([
            'success' => true,
            'scores' => $scores
        ]);
    }

    public function showJobMatch()
    {
        $jobs = Job::with(['employer', 'category', 'jobType'])
                   ->where('status', 'active')
                   ->orderBy('created_at', 'desc')
                   ->get();
                   
        $categories = Category::orderBy('name')->get();
        $jobTypes = JobType::orderBy('name')->get();
        
        return view('front.account.ai.job-match', [
            'jobs' => $jobs,
            'categories' => $categories,
            'jobTypes' => $jobTypes
        ]);
    }
} 