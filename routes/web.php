<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\JobTypeController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AIFeaturesController;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{id}',[JobsController::class,'jobDetail'])->name('jobDetail');
// Guest routes for saving jobs will be handled in authenticated group
// Forgot password
Route::get('/forgot-password',[AccountController::class,'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password',[AccountController::class,'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}',[AccountController::class,'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password',[AccountController::class,'processResetPassword'])->name('account.processResetPassword');

Route::group(['prefix' => 'admin','middleware' => 'checkRole'], function () {
    // only admin can get this route
        Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/users',[UserController::class,'index'])->name('admin.users');
        Route::delete('/users',[UserController::class,'destroy'])->name('admin.user.destroy');
        Route::get('/user/{id}',[UserController::class,'edit'])->name('admin.user.edit');
        Route::put('/user/{id}',[UserController::class,'update'])->name('admin.user.update');
        // Routes for Job Applications
        Route::get('/job-applications',[JobApplicationController::class,'index'])->name('admin.jobApplications');
        Route::delete('/job-applications',[JobApplicationController::class,'destroy'])->name('admin.jobApplications.destroy');
        // Routes for Jobs
        Route::get('/jobs',[JobController::class,'index'])->name('admin.jobs');
        Route::get('/jobs/create',[JobController::class,'create'])->name('admin.jobs.create');
        Route::post('/jobs/save',[JobController::class,'save'])->name('admin.job.save');
        Route::delete('/jobs',[JobController::class,'destroy'])->name('admin.job.destroy');
        Route::get('/job/edit/{id}',[JobController::class,'edit'])->name('admin.job.edit');
        Route::put('/update-job/{jobId}',[JobController::class,'update'])->name('admin.job.update');
        // Routes for Categories
        Route::get('/create-category',[CategoryController::class,'create'])->name('admin.categories.create');
        Route::post('/create-category',[CategoryController::class,'save'])->name('admin.categories.save');
        Route::get('/categories',[CategoryController::class,'index'])->name('admin.categories');
        Route::get('/category/edit/{id}',[CategoryController::class,'edit'])->name('admin.categories.edit');
        Route::put('/category/{id}',[CategoryController::class,'update'])->name('admin.categories.update');
        Route::delete('/categories',[CategoryController::class,'destroy'])->name('admin.categories.destroy');
        // Job Types
        Route::get('/job-types',[JobTypeController::class,'index'])->name('admin.job_types');
        Route::delete('/job-types/{id}',[JobTypeController::class,'destroy'])->name('admin.job_types.destroy');
        Route::get('/create-job-types',[JobTypeController::class,'create'])->name('admin.job_types.create');
        Route::post('/save-job-types',[JobTypeController::class,'save'])->name('admin.job_types.save');
        Route::get('/edit-job-types/{id}',[JobTypeController::class,'edit'])->name('admin.job_types.edit');
        Route::put('/update-job-types/{id}',[JobTypeController::class,'update'])->name('admin.job_types.update');
        
        // Analytics Routes
        Route::get('/analytics',[AnalyticsController::class,'dashboard'])->name('admin.analytics');
        Route::get('/analytics/job-clusters',[AnalyticsController::class,'jobClusters'])->name('admin.analytics.jobClusters');
        Route::get('/analytics/user-clusters',[AnalyticsController::class,'userClusters'])->name('admin.analytics.userClusters');
    });

// Middleware
Route::group(['prefix' => 'account'], function () {

    // Guest Route
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/register',[AccountController::class,'registration'])->name('account.registration');
        Route::post('/process-register',[AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('/login',[AccountController::class,'login'])->name('account.login');
        Route::post('/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });

    // Authenticate Route
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
        Route::put('/update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
        Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('/update-profile-img',[AccountController::class,'updateProfileImg'])->name('account.updateProfileImg');
        
        // Job Management Routes
        Route::post('/apply-job',[AccountController::class,'applyJob'])->name('account.applyJob');
        Route::post('/save-job',[AccountController::class,'saveJobToFavorites'])->name('account.saveJob');
        Route::get('/create-job',[AccountController::class,'createJob'])->name('account.createJob');
        Route::post('/store-job',[AccountController::class,'storeJob'])->name('account.storeJob');
        Route::get('/my-jobs',[AccountController::class,'myJobs'])->name('account.myJobs');
        Route::get('/edit-job/edit/{jobId}',[AccountController::class,'editJob'])->name('account.editJob');
        Route::post('/update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
        Route::post('/delete-job',[AccountController::class,'deleteJob'])->name('account.deleteJob');
        Route::get('/my-job-applications',[AccountController::class,'myJobApplications'])->name('account.myJobApplications');
        Route::post('/remove-job-application',[AccountController::class,'removeJobs'])->name('account.removeJobs');
        Route::get('/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('/remove-saved-job',[AccountController::class,'removeSavedJob'])->name('account.removeSavedJob');
        
        // AI-Powered Features
        Route::prefix('ai')->group(function () {
            Route::get('/resume-builder', function () {
                return view('front.account.ai.resume-builder');
            })->name('ai.resumeBuilder');
            
            Route::get('/job-match', [AIFeaturesController::class, 'showJobMatch'])->name('ai.jobMatch');

            Route::post('/generate-resume', [AIFeaturesController::class, 'generateResume'])->name('ai.generateResume');
            Route::post('/analyze-resume', [AIFeaturesController::class, 'analyzeResume'])->name('ai.analyzeResume');
            Route::post('/job-match', [AIFeaturesController::class, 'getJobMatch'])->name('ai.jobMatch');
            Route::post('/job-match-scores', [AIFeaturesController::class, 'getJobMatchScores'])->name('ai.jobMatchScores');

            // Test route for OpenAI integration
            Route::get('/test-openai', function () {
                $result = OpenAI::chat()->create([
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'user', 'content' => 'Say hello!']
                    ]
                ]);
                return response()->json(['response' => $result->choices[0]->message->content]);
            });

            // Test route for resume analysis
            Route::get('/test-resume-analysis', function () {
                $sampleResume = "John Doe\nSoftware Engineer\n\nExperience:\n- Senior Developer at Tech Corp (2018-2023)\n- Full Stack Developer at Web Solutions (2015-2018)\n\nSkills:\n- PHP, Laravel, MySQL\n- JavaScript, Vue.js, React\n- Git, Docker, AWS\n\nEducation:\n- Bachelor's in Computer Science";
                
                $aiFeatures = app(App\Http\Controllers\AIFeaturesController::class);
                return $aiFeatures->analyzeResume(new \Illuminate\Http\Request(['resume_text' => $sampleResume]));
            });
        });
        
        // Change Password
        Route::post('/change-password',[AccountController::class,'changePassword'])->name('account.changePassword');
        
        // Job Recommendations
        Route::get('/job-recommendations',[AnalyticsController::class,'jobRecommendations'])->name('account.jobRecommendations');
        Route::get('/candidate-recommendations/{jobId}',[AnalyticsController::class,'candidateRecommendations'])->name('account.candidateRecommendations');
    });

});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkAdmin'])->group(function () {
    // Jobs management
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // Applications management
    Route::patch('/applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});

// Job Seeker Routes
Route::middleware(['auth'])->group(function () {
    // KYC Routes
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', 'KycController@index')->name('index');
        Route::get('/create', 'KycController@create')->name('create');
        Route::post('/', 'KycController@store')->name('store');
        Route::get('/{document}', 'KycController@show')->name('show');
        Route::get('/{document}/download', 'KycController@download')->name('download');
    });
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // KYC Management
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', 'KycController@adminIndex')->name('index');
        Route::post('/{document}/verify', 'KycController@verify')->name('verify');
        Route::post('/{document}/reject', 'KycController@reject')->name('reject');
    });
});

// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'check.admin']], function () {
    // Settings Routes
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [AdminSettingsController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Roles & Permissions
    Route::get('/settings/roles', [AdminSettingsController::class, 'roles'])->name('admin.settings.roles');
    Route::post('/settings/roles/{id}', [AdminSettingsController::class, 'updateRole'])->name('admin.settings.roles.update');
    
    // Security & Logs
    Route::get('/settings/security-log', [AdminSettingsController::class, 'securityLog'])->name('admin.settings.security-log');
    Route::get('/settings/audit-log', [AdminSettingsController::class, 'auditLog'])->name('admin.settings.audit-log');
    
    // Backup Management
    Route::get('/settings/backup', [AdminSettingsController::class, 'backup'])->name('admin.settings.backup');
    Route::post('/settings/backup', [AdminSettingsController::class, 'createBackup'])->name('admin.settings.backup.create');
    
    // Analytics Routes
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard'])->name('admin.analytics.dashboard');
    Route::get('/analytics/job-clusters', [AnalyticsController::class, 'jobClusters'])->name('admin.analytics.job-clusters');
    Route::get('/analytics/user-clusters', [AnalyticsController::class, 'userClusters'])->name('admin.analytics.user-clusters');
});
