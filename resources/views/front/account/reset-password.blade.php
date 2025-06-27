@extends('front.layouts.app')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-wrapper">
                    <div class="auth-form-side w-100">
                        <div class="auth-form-content">
                            <div class="logo-section mb-4">
                                <i class="fas fa-key"></i>
                            </div>
                            <h1>Set New Password</h1>
                            <p class="subtitle">Choose a strong password for your account</p>

                            @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                            @endif

                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                            @endif

                            <form action="{{ route('account.processResetPassword') }}" method="post">
                                @csrf
                                <input type="hidden" name="token" value="{{ $tokenString }}">
                                
                                <div class="mb-4">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password" 
                                            class="form-control @error('new_password') is-invalid @enderror" 
                                            placeholder="••••••••">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password', 'togglePasswordIcon1')">
                                            <i class="fas fa-eye-slash" id="togglePasswordIcon1"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="confirm_password" 
                                            class="form-control @error('confirm_password') is-invalid @enderror" 
                                            placeholder="••••••••">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password', 'togglePasswordIcon2')">
                                            <i class="fas fa-eye-slash" id="togglePasswordIcon2"></i>
                                        </button>
                                    </div>
                                    @error('confirm_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="auth-btn">
                                    Reset Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Reuse the same styles from registration.blade.php */
.auth-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 2rem 0;
    background: #f5f7fa;
}

.auth-wrapper {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.auth-form-side {
    padding: 3rem;
}

.auth-form-content {
    max-width: 400px;
    margin: 0 auto;
}

.logo-section {
    text-align: center;
    font-size: 2rem;
    color: #1e2a78;
}

.auth-form-content h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-align: center;
}

.subtitle {
    text-align: center;
    color: #6c757d;
    margin-bottom: 2rem;
}

.auth-btn {
    width: 100%;
    padding: 0.8rem;
    background: #1e2a78;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    margin-top: 1rem;
    transition: all 0.3s;
}

.auth-btn:hover {
    background: #161f5c;
}

.input-group {
    position: relative;
}

.form-control {
    padding-right: 3rem;
}

.form-control:focus {
    border-color: #1e2a78;
    box-shadow: none;
}

.btn-outline-secondary {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-color: #eee;
    background: #f8f9fa;
}

.btn-outline-secondary:hover {
    background: #e9ecef;
    border-color: #ddd;
}

.btn-outline-secondary:focus {
    box-shadow: none;
    border-color: #1e2a78;
}
</style>

@endsection

@section('scripts')
<script>
// Password toggle function
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection
