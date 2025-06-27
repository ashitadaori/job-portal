<?php
namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    // This method will show user registration page
    public function registration(){
        return view('front.account.registration');
    }

    // This method will save a user
    public function processRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
            'role' => 'required|in:jobseeker,employer'
        ]);

        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            // Create corresponding profile based on role
            if ($request->role === 'employer') {
                $user->employerProfile()->create([]);
            } else {
                $user->jobSeekerProfile()->create([]);
            }

            $message = "You have registered successfully.";
            Session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message,
                'redirect' => url('/account/login')
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // This method will show user login page
    public function login(){
        return view('front.account.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')
                    ->withInput($request->only('email'))
                    ->with('error','Either email/password is incorrect');
            }
        }else{
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile(){

        // here we will get user id, which user logged in
        $id = Auth::user()->id;
        $user = User::where('id',$id)->first();

        return view('front.account.profile',[
            'user' => $user,
        ]);
    }

    // update profile function
    public function updateProfile(Request $request){

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->passes()){

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            Session()->flash('success','Profile updated successfully');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // /upload/change image from profile
    public function updateProfileImg(Request $request){
        // dd($request->all());
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image|max:2048' // Adjust max size as needed
        ]);
        if($validator->passes()){

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_img/'), $imageName);

            // Create directory if it doesn't exist
            if (!file_exists(public_path('/profile_img/thumb/'))) {
                mkdir(public_path('/profile_img/thumb/'), 0777, true);
            }

            try {
                // Just copy the image for now instead of resizing
                $sourcePath = public_path().'/profile_img/'.$imageName;
                $destPath = public_path().'/profile_img/thumb/'.$imageName;
                
                // Simple file copy instead of image processing
                if (!copy($sourcePath, $destPath)) {
                    throw new \Exception("Failed to copy image file");
                }
                
                // delete old profile image, when user update his/her new image
                if (Auth::user()->image) {
                    File::delete(public_path('/profile_img/'. Auth::user()->image));
                    File::delete(public_path('/profile_img/thumb/'. Auth::user()->image));
                }

                User::where('id',$id)->update(['image' => $imageName]);

                Session()->flash('success','Profile image updated successfully.');
                return response()->json([
                    'status' => true,
                    'errors' => [],
                ]);
            } catch (\Exception $e) {
                // Log the error
                \Log::error('Image processing error: ' . $e->getMessage(), [
                    'userId' => $id,
                    'exception' => $e
                ]);
                
                return response()->json([
                    'status' => false,
                    'errors' => ['image' => 'Error processing image: ' . $e->getMessage()],
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }


    public function createJob(){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $job_types = JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('front.account.job.create',[
            'categories' => $categories,
            'job_types' => $job_types,
        ]);
    }

    // Method for employers to create new job listings
    public function storeJob(Request $request){
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'description_wysiwyg' => 'required', // Rules for the WYSIWYG editor
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){

            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->employer_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            Session()->flash('success','Job added successfully.');
            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // Method for job seekers to save jobs they're interested in
    public function saveJobToFavorites(Request $request) {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Check if job exists
        $job = Job::find($request->job_id);
        if (!$job) {
            return response()->json([
                'status' => false,
                'errors' => ['job_id' => 'Job not found']
            ]);
        }

        // Check if already saved
        $existingSave = SavedJob::where('user_id', Auth::user()->id)
            ->where('job_id', $request->job_id)
            ->first();

        if ($existingSave) {
            return response()->json([
                'status' => false,
                'errors' => ['general' => 'You have already saved this job']
            ]);
        }

        try {
            // Save the job
            $savedJob = new SavedJob();
            $savedJob->user_id = Auth::user()->id;
            $savedJob->job_id = $request->job_id;
            $savedJob->save();

            Session::flash('success', 'Job saved successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Job saved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Save job error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'errors' => ['general' => 'There was an error saving the job. Please try again.']
            ]);
        }
    }

    // Show all jobs
    public function myJobs(){
        //metioned the code of the paginator in AppServiceProvider Class otherwise your paginator will not work perfectly
        $jobs = Job::where('employer_id', Auth::user()->id)
            ->with('jobType')
            ->orderBy('created_at','DESC')
            ->paginate(10);
            
        return view('front.account.job.my-jobs',[
            'jobs' => $jobs,
        ]);
    }

    // edit Job page
    public function editJob($id){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        // If user write another user id through url show him 404 page
        $job = Job::where([
            'employer_id' => Auth::user()->id,
            'id' => $id,
        ])->first();

        if($job == null){
            abort(404);
        }

        return view('front.account.job.edit',[
            'categories' => $categories,
            'job_types' => $jobTypes,
            'job' => $job,
        ]);
    }

    // Update Job
    public function updateJob($jobId, Request $request){
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $job = Job::find($jobId);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->employer_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            Session()->flash('success','Job updated successfully.');
            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    // This method will delete job
    public function deleteJob(Request $request){
        $job = Job::where([
            'employer_id' => Auth::user()->id,
            'id' => $request->jobId,
        ])->first();

        if($job == null){
            Session()->flash('error','Either Job deleted or not found.');
            return response()->json([
                'status' => true,
            ]);
        }

        Job::where('id',$request->jobId)->delete();
        Session()->flash('success','Job deleted successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function myJobApplications(){

        $jobApplications = JobApplication::where('user_id',Auth::user()->id)->with(['job','job.jobType','job.applications'])->orderBy('created_at','DESC')->paginate(10);

        return view('front.account.job.my-job-application',[
            'jobApplications' => $jobApplications,
        ]);
    }


    // remove applied jobs
    public function removeJobs(Request $request){

        $jobApplication = JobApplication::where([
                                    'id' => $request->id,
                                    'user_id' => Auth::user()->id,]
                                )->first();
        if($jobApplication == null){
            Session()->flash('error','Job application not found');
            return response()->json([
                'status' => false,
            ]);
        }
        JobApplication::find($request->id)->delete();
        Session()->flash('success','Job application removed successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    // This function will show all saved jobs
    public function savedJobs(){
        $savedJobs = SavedJob::where('user_id',Auth::user()->id)
        ->with(['job','job.jobType','job.category'])
        ->paginate(6);
        return view('front.account.job.saved-jobs',[
            'savedJobs' => $savedJobs
        ]);
    }

    public function applyJob(Request $request) {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer',
            'cover_letter' => 'required|min:50',
            'resume' => 'required|mimes:pdf,doc,docx|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Check if user has already applied
        $existingApplication = JobApplication::where('user_id', Auth::user()->id)
            ->where('job_id', $request->job_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'status' => false,
                'errors' => ['general' => 'You have already applied for this job.']
            ]);
        }

        try {
            // Handle resume upload
            $resume = $request->file('resume');
            $resumeName = time() . '_' . Auth::user()->id . '_' . $resume->getClientOriginalName();
            $resume->move(public_path('resumes'), $resumeName);

            // Create job application
            $application = new JobApplication();
            $application->user_id = Auth::user()->id;
            $application->job_id = $request->job_id;
            $application->cover_letter = $request->cover_letter;
            $application->resume = $resumeName;
            $application->status = 'pending';
            $application->save();

            Session::flash('success', 'Your job application has been submitted successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Application submitted successfully'
            ]);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Job application error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'errors' => ['general' => 'There was an error submitting your application. Please try again.']
            ]);
        }
    }

    public function removeSavedJob(Request $request){

        $savedJob = SavedJob::where([
                                    'id' => $request->id,
                                    'user_id' => Auth::user()->id,]
                                )->first();
        if($savedJob == null){
            Session()->flash('error','Job not found');
            return response()->json([
                'status' => false,
            ]);
        }
        SavedJob::find($request->id)->delete();
        Session()->flash('success','Job removed successfully.');
        return response()->json([
            'status' => true,
        ]);
    }


    // Change password function
    public function changePassword(Request $request){
        $data = [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ];
        $validator = Validator::make($request->all(),$data);
        if($validator->passes()){

            $user = User::select('id','password')->where('id',Auth::user()->id)->first();
            if(!Hash::check($request->old_password, $user->password)){

                Session::flash('error','Your old password is incorrect, Please try again');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            Session()->flash('success','Your have successfully change your password');
            return response()->json([
                'status' => true,
            ]);

        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // Forgot passowrd

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
        ]);

        if($validator->fails()){
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        try {
            $token = Str::random(60);

            \DB::table('password_resets')->where('email', $request->email)->delete();

            \DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => now(),
            ]);

            // Send Email here
            $user = User::where('email',$request->email)->first();
            $mailData = [
                'token' => $token,
                'user' => $user,
                'subject' => 'You have requested to change your password.',
            ];
            
            Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

            return redirect()->route('account.forgotPassword')->with('success','Reset password email has been sent to your inbox');
            
        } catch (\Exception $e) {
            \Log::error('Password Reset Email Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('account.forgotPassword')
                ->with('error', 'There was an error sending the password reset email. Please try again later.');
        }
    }

    public function resetPassword($tokenString){

        $token = \DB::table('password_resets')->where('token',$tokenString)->first();
        if($token == null){
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }

        return view('front.account.reset-password',[
            'tokenString' => $tokenString,
        ]);
    }

    public function processResetPassword(Request $request){

        $token = \DB::table('password_resets')->where('token',$request->token)->first();
        if($token == null){
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password',
        ]);
        if($validator->fails()){
            return redirect()->route('account.resetPassword',$request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('account.login',$request->token)->with('success','You have successfully changed your password.');


    }

}
