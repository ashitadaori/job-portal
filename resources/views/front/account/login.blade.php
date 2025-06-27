@extends('front.layouts.app')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="auth-wrapper">
                    <!-- Left side - Login Form -->
                    <div class="auth-form-side">
                        <div class="auth-form-content">
                            <div class="logo-section mb-4">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h1>Welcome back</h1>
                            <p class="subtitle">Please Enter your Account details</p>

                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ Session::get('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('account.authenticate') }}" method="post" name="loginForm" id="loginForm">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <input type="email" value="{{ old('email') }}" name="email" id="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            placeholder="Johndoe@gmail.com">
                                        <span class="input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="password" class="form-label mb-0">Password</label>
                                        <a href="{{ route('account.forgotPassword') }}" class="forgot-link">
                                            Forgot Password
                                        </a>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" 
                                            class="form-control @error('password') is-invalid @enderror" 
                                            placeholder="••••••••">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                            <i class="fas fa-eye-slash" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">Keep me logged in</label>
                                    </div>
                                </div>

                                <button type="submit" class="auth-btn">
                                    Sign in
                                </button>

                                <div class="social-login">
                                    <div class="divider">
                                        <span>or sign in with</span>
                                    </div>
                                    <div class="social-buttons">
                                        <a href="#" class="social-button">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google">
                                        </a>
                                        <a href="#" class="social-button">
                                            <i class="fab fa-github"></i>
                                        </a>
                                        <a href="#" class="social-button">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <div class="auth-footer">
                                Don't have an account? <a href="{{ route('account.registration') }}">Sign up</a>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - Testimonial -->
                    <div class="auth-testimonial-side">
                        <div class="testimonial-content">
                            <h2>What's our Jobseekers Said.</h2>
                            <div class="testimonial-text">
                                <blockquote>
                                    "Search and find your dream job is now easier than ever. Just browse a job and apply if you need to."
                                </blockquote>
                                <div class="testimonial-author">
                                    <div class="author-info">
                                        <h4>Mas Parjono</h4>
                                        <p>UI Designer at Google</p>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-nav">
                                <button class="nav-btn prev">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <button class="nav-btn next">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                            <div class="info-box">
                                <h3>Get your right job and right place apply now</h3>
                                <p>Be among the first founders to experience the easiest way to start run a business.</p>
                                <div class="user-avatars">
                                    <img src="https://i.pravatar.cc/40?img=1" alt="User">
                                    <img src="https://i.pravatar.cc/40?img=2" alt="User">
                                    <img src="https://i.pravatar.cc/40?img=3" alt="User">
                                    <img src="https://i.pravatar.cc/40?img=4" alt="User">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 2rem 0;
    background: #f5f7fa;
}

.auth-wrapper {
    display: flex;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.auth-form-side {
    width: 45%;
    padding: 3rem;
    background: #fff;
}

.auth-testimonial-side {
    width: 55%;
    background: linear-gradient(135deg, #1e2a78 0%, #ff6b6b 100%);
    padding: 3rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.logo-section {
    text-align: center;
}

.logo-section i {
    font-size: 2rem;
    color: #1e2a78;
    background: rgba(30, 42, 120, 0.1);
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
}

.auth-form-content h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e2a78;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    margin-bottom: 2rem;
}

.form-label {
    color: #1e2a78;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.input-group {
    position: relative;
}

.form-control {
    padding: 0.8rem 1rem;
    border-radius: 12px;
    border: 2px solid #eee;
    background: #f8f9fa;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #1e2a78;
    box-shadow: 0 0 0 4px rgba(30, 42, 120, 0.1);
}

.input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.forgot-link {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.forgot-link:hover {
    text-decoration: underline;
}

.form-check-label {
    color: #666;
    font-size: 0.9rem;
}

.form-check-input {
    border-color: #1e2a78;
}

.form-check-input:checked {
    background-color: #1e2a78;
    border-color: #1e2a78;
}

.auth-btn {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 12px;
    background: #1e2a78;
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.auth-btn:hover {
    background: #ff6b6b;
    transform: translateY(-2px);
}

.social-login {
    margin-top: 2rem;
}

.divider {
    text-align: center;
    position: relative;
    margin: 1.5rem 0;
}

.divider::before,
.divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 45%;
    height: 1px;
    background: #eee;
}

.divider::before {
    left: 0;
}

.divider::after {
    right: 0;
}

.divider span {
    background: #fff;
    padding: 0 1rem;
    color: #666;
    font-size: 0.9rem;
}

.social-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.social-button {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    border: 2px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    transition: all 0.3s ease;
}

.social-button img {
    width: 20px;
    height: 20px;
}

.social-button:hover {
    border-color: #1e2a78;
    color: #1e2a78;
    transform: translateY(-2px);
}

.auth-footer {
    text-align: center;
    margin-top: 2rem;
    color: #666;
}

.auth-footer a {
    color: #1e2a78;
    font-weight: 600;
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}

/* Testimonial Side Styling */
.testimonial-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.testimonial-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
}

.testimonial-text {
    margin-bottom: 2rem;
}

.testimonial-text blockquote {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-info h4 {
    font-size: 1.1rem;
    margin-bottom: 0.2rem;
}

.author-info p {
    margin: 0;
    opacity: 0.8;
}

.testimonial-nav {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.nav-btn {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-btn:hover {
    background: rgba(255,255,255,0.2);
}

.info-box {
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 2rem;
}

.info-box h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.info-box p {
    opacity: 0.8;
    margin-bottom: 1.5rem;
}

.user-avatars {
    display: flex;
    align-items: center;
}

.user-avatars img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #fff;
    margin-left: -10px;
}

.user-avatars img:first-child {
    margin-left: 0;
}

.toggle-password {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-color: #eee;
    background: #f8f9fa;
}
.toggle-password:hover {
    background: #e9ecef;
    border-color: #ddd;
}
.toggle-password:focus {
    box-shadow: none;
    border-color: #1e2a78;
}

@media (max-width: 991px) {
    .auth-wrapper {
        flex-direction: column;
    }
    
    .auth-form-side,
    .auth-testimonial-side {
        width: 100%;
    }
    
    .auth-testimonial-side {
        padding: 2rem;
    }
}

@media (max-width: 576px) {
    .auth-form-side {
        padding: 2rem;
    }
    
    .testimonial-content h2 {
        font-size: 2rem;
    }
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle function
    window.togglePassword = function(inputId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    };

    // Form validation
    $("#loginForm").submit(function(e) {
        var isValid = true;
        
        if ($("#email").val() == '') {
            $("#email").addClass('is-invalid')
                .siblings('.invalid-feedback').text('Please enter your email address.');
            isValid = false;
        } else {
            $("#email").removeClass('is-invalid');
        }
        
        if ($("#password").val() == '') {
            $("#password").addClass('is-invalid')
                .siblings('.invalid-feedback').text('Please enter your password.');
            isValid = false;
        } else {
            $("#password").removeClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

