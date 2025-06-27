<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalJobs = Job::count();
        $totalApplications = JobApplication::count();
        $totalEmployers = User::where('role', 'employer')->count();
        $totalJobSeekers = User::where('role', 'user')->count();
        
        // Get security logs
        $securityLogs = DB::table('security_logs')
            ->join('users', 'security_logs.user_id', '=', 'users.id')
            ->select('security_logs.*', 'users.name')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get system health
        $systemHealth = [
            'disk_usage' => disk_free_space('/') / disk_total_space('/') * 100,
            'memory_usage' => memory_get_usage(true) / 1024 / 1024,
            'last_backup' => DB::table('backups')->latest()->first()?->created_at ?? 'No backups',
            'pending_jobs' => DB::table('jobs')->count(),
        ];

        // Get recent jobs with application count and enhanced stats
        $recentJobs = Job::with(['employer', 'category', 'type'])
            ->withCount(['applications', 'savedJobs as favorites_count'])
            ->withAvg('applications', 'expected_salary')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent applications with related data
        $recentApplications = JobApplication::with(['user', 'job'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($application) {
                $application->status_color = $this->getStatusColor($application->status);
                return $application;
            });

        // Get recent activities (system events)
        $recentActivities = DB::table('activity_log')
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select('activity_log.*', 'users.name')
            ->orderBy('activity_log.created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($activity) {
                $activity->icon = $this->getActivityIcon($activity->description);
                return $activity;
            });

        // Get monthly stats for the chart with enhanced metrics
        $monthlyStats = $this->getMonthlyStats();

        // Get user engagement metrics
        $userEngagement = [
            'active_users' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
            'new_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'application_rate' => JobApplication::where('created_at', '>=', now()->subDays(30))
                ->count() / max(1, Job::where('created_at', '>=', now()->subDays(30))->count()),
            'average_applications_per_job' => JobApplication::count() / max(1, Job::count()),
        ];

        return view('admin.dashboard', compact(
            'totalJobs',
            'totalApplications',
            'totalEmployers',
            'totalJobSeekers',
            'recentJobs',
            'recentApplications',
            'recentActivities',
            'monthlyStats',
            'securityLogs',
            'systemHealth',
            'userEngagement'
        ));
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'reviewing' => 'info',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    private function getActivityIcon($description)
    {
        // Define icons based on activity type
        if (str_contains($description, 'job')) {
            return 'briefcase';
        } elseif (str_contains($description, 'application')) {
            return 'file-alt';
        } elseif (str_contains($description, 'user')) {
            return 'user';
        } elseif (str_contains($description, 'category')) {
            return 'folder';
        } else {
            return 'bell';
        }
    }

    private function getMonthlyStats()
    {
        $months = collect([]);
        $jobData = collect([]);
        $applicationData = collect([]);
        $userEngagementData = collect([]);
        $employerActivityData = collect([]);

        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M'));

            // Count jobs created in this month
            $jobCount = Job::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $jobData->push($jobCount);

            // Count applications submitted in this month
            $applicationCount = JobApplication::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $applicationData->push($applicationCount);

            // User engagement (active users)
            $activeUsers = User::where('last_login_at', '>=', $date->startOfMonth())
                ->where('last_login_at', '<=', $date->endOfMonth())
                ->count();
            $userEngagementData->push($activeUsers);

            // Employer activity (new job posts + application responses)
            $employerActivity = Job::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count() + 
                JobApplication::whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->whereNotNull('employer_response')
                ->count();
            $employerActivityData->push($employerActivity);
        }

        return [
            'months' => $months,
            'jobs' => $jobData,
            'applications' => $applicationData,
            'user_engagement' => $userEngagementData,
            'employer_activity' => $employerActivityData,
        ];
    }
}
