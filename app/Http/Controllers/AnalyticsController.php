<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\User;
use App\Services\KMeansClusteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $clusteringService;
    
    public function __construct(KMeansClusteringService $clusteringService)
    {
        $this->clusteringService = $clusteringService;
        $this->middleware('auth');
    }
    
    /**
     * Display the labor market insights dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get labor market insights
        $insights = $this->clusteringService->getLaborMarketInsights();
        
        // Get counts for dashboard
        $totalJobs = Job::where('status', 1)->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalApplications = JobApplication::count();
        $totalCategories = Category::where('status', 1)->count();
        
        return view('admin.analytics.dashboard', [
            'insights' => $insights,
            'totalJobs' => $totalJobs,
            'totalUsers' => $totalUsers,
            'totalApplications' => $totalApplications,
            'totalCategories' => $totalCategories
        ]);
    }
    
    /**
     * Display job recommendations for the current user
     * 
     * @return \Illuminate\View\View
     */
    public function jobRecommendations()
    {
        $userId = Auth::id();
        $recommendedJobs = $this->clusteringService->getJobRecommendations($userId, 10);
        
        return view('front.account.job.recommendations', [
            'recommendedJobs' => $recommendedJobs
        ]);
    }
    
    /**
     * Display candidate recommendations for a job
     * 
     * @param int $jobId
     * @return \Illuminate\View\View
     */
    public function candidateRecommendations($jobId)
    {
        $job = Job::findOrFail($jobId);
        
        // Check if the current user owns this job
        if ($job->user_id != Auth::id() && Auth::user()->role != 'admin') {
            return redirect()->route('account.myJobs')->with('error', 'You do not have permission to view this page.');
        }
        
        $recommendedUsers = $this->clusteringService->getUserRecommendations($jobId, 10);
        
        return view('front.account.job.candidate-recommendations', [
            'job' => $job,
            'recommendedUsers' => $recommendedUsers
        ]);
    }
    
    /**
     * Display job clusters visualization
     * 
     * @return \Illuminate\View\View
     */
    public function jobClusters()
    {
        $clusters = $this->clusteringService->runJobClustering();
        
        return view('admin.analytics.job-clusters', [
            'clusters' => $clusters
        ]);
    }
    
    /**
     * Display user clusters visualization
     * 
     * @return \Illuminate\View\View
     */
    public function userClusters()
    {
        $clusters = $this->clusteringService->runUserClustering();
        
        return view('admin.analytics.user-clusters', [
            'clusters' => $clusters
        ]);
    }
}
