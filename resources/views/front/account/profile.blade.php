@extends('front.layouts.app')

@section('content')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                
                <!-- Personal Information Card -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="fs-4 mb-1">Personal Information</h3>
                                <p class="mb-0 text-muted">Update your personal details</p>
                            </div>
                            <div class="profile-image-container">
                                @if (Auth::user()->image != '')
                                    <img src="{{ asset('profile_img/thumb/'.Auth::user()->image) }}" alt="Profile" class="profile-image">
                                @else
                                    <img src="{{ asset('assets/images/avatar7.png') }}" alt="Profile" class="profile-image">
                                @endif
                                <button type="button" class="change-photo-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        
                        <form action="" method="post" id="userForm" name="userForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="name" class="mb-2">Full Name<span class="req">*</span></label>
                                    <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control" placeholder="Enter your name">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="email" class="mb-2">Email Address<span class="req">*</span></label>
                                    <input type="email" value="{{ $user->email }}" name="email" id="email" class="form-control" placeholder="Enter your email">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="designation" class="mb-2">Designation</label>
                                    <input type="text" value="{{ $user->designation }}" name="designation" id="designation" class="form-control" placeholder="Enter your designation">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="mobile" class="mb-2">Mobile Number</label>
                                    <input type="text" value="{{ $user->mobile }}" name="mobile" id="mobile" class="form-control" placeholder="Enter your mobile number">
                                </div>
                            </div>

                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form p-4">
                        <h3 class="fs-4 mb-1">Change Password</h3>
                        <p class="mb-4 text-muted">Ensure your account is using a long, random password to stay secure</p>
                        
                        <form action="" method="post" name="changePasswordForm" id="changePasswordForm">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="old_password" class="mb-2">Current Password<span class="req">*</span></label>
                                    <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter current password">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="new_password" class="mb-2">New Password<span class="req">*</span></label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="confirm_password" class="mb-2">Confirm Password<span class="req">*</span></label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm new password">
                                    <p></p>
                                </div>
                            </div>

                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary" id="submit">
                                    <i class="fas fa-lock"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Stats Card -->
                <div class="card border-0 shadow">
                    <div class="card-body card-form p-4">
                        <h3 class="fs-4 mb-1">Account Statistics</h3>
                        <p class="mb-4 text-muted">Overview of your account activity</p>
                        
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="stat-box p-3 rounded bg-light">
                                    <h4 class="mb-1">{{ Auth::user()->role == 'employer' ? Auth::user()->jobs()->count() : 0 }}</h4>
                                    <p class="mb-0 text-muted">{{ Auth::user()->role == 'employer' ? 'Jobs Posted' : 'Applications' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-box p-3 rounded bg-light">
                                    <h4 class="mb-1">{{ Auth::user()->jobApplications()->count() }}</h4>
                                    <p class="mb-0 text-muted">{{ Auth::user()->role == 'employer' ? 'Total Applicants' : 'Jobs Applied' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-box p-3 rounded bg-light">
                                    <h4 class="mb-1">{{ Auth::user()->savedJobs()->count() }}</h4>
                                    <p class="mb-0 text-muted">Saved Jobs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Picture Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mx-3">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image-container {
    position: relative;
    width: 100px;
    height: 100px;
}

.profile-image {
    width: 100%;
    height: 100%;
    border-radius: 15px;
    object-fit: cover;
    border: 4px solid #f8fafc;
}

.change-photo-btn {
    position: absolute;
    bottom: -5px;
    right: -5px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.change-photo-btn:hover {
    background: #f8fafc;
    transform: scale(1.1);
}

.change-photo-btn i {
    color: #3b82f6;
    font-size: 14px;
}

.stat-box {
    transition: all 0.3s ease;
}

.stat-box:hover {
    background-color: #e2e8f0 !important;
    transform: translateY(-2px);
}

.req {
    color: red;
    margin-left: 2px;
}

.card-footer {
    background: transparent;
    border-top: none;
    padding-bottom: 0 !important;
}

.btn-primary {
    padding: 10px 20px;
    font-size: 14px;
}

.btn-primary i {
    margin-right: 8px;
}
</style>

@endsection

@section('customJs')
<script type="text/javascript">
    $("#userForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: "put",
            url: "{{ route('account.updateProfile') }}",
            data: $("#userForm").serializeArray(),
            dataType: "json",
            success: function (response) {
                if(response.status == true) {
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    window.location.href = "{{ route('account.profile') }}";
                } else {
                    var errors = response.errors;
                    if(errors.name) {
                        $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                    } else {
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if(errors.email) {
                        $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }
                }
            }
        });
    });

    $("#changePasswordForm").submit(function (e) {
        e.preventDefault();
        $("#submit").prop('disabled', true);

        $.ajax({
            type: "post",
            url: "{{ route('account.changePassword') }}",
            data: $("#changePasswordForm").serializeArray(),
            dataType: "json",
            success: function (response) {
                $("#submit").prop('disabled', false);

                if(response.status == true) {
                    $("#old_password, #new_password, #confirm_password")
                        .removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    window.location.href = "{{ route('account.profile') }}";
                } else {
                    var errors = response.errors;
                    if(errors.old_password) {
                        $("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.old_password);
                    } else {
                        $("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if(errors.new_password) {
                        $("#new_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.new_password);
                    } else {
                        $("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if(errors.confirm_password) {
                        $("#confirm_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.confirm_password);
                    } else {
                        $("#confirm_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }
                }
            }
        });
    });
</script>
@endsection
