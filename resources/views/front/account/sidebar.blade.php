<!-- Modern Sidebar -->
<div class="modern-sidebar-content">
    <!-- Profile Card -->
    <div class="profile-quick-view">
        <div class="profile-image">
                @if (Auth::user()->image != '')
                <img src="{{ asset('profile_img/thumb/'.Auth::user()->image) }}" alt="Profile">
                @else
                <img src="{{ asset('assets/images/avatar7.png') }}" alt="Profile">
                @endif
            <button type="button" class="change-photo-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-camera"></i>
            </button>
                </div>
        <div class="profile-info">
            <h5>{{ Auth::user()->name }}</h5>
            <p>{{ Auth::user()->designation ?: 'Update your designation' }}</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu">
        <a href="{{ route('account.profile') }}" class="menu-item {{ Request::is('account/profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Account Settings</span>
        </a>

        <a href="{{ route('account.createJob') }}" class="menu-item {{ Request::is('account/create-job') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i>
            <span>Post a Job</span>
        </a>

        <a href="{{ route('account.myJobs') }}" class="menu-item {{ Request::is('account/my-jobs') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i>
            <span>My Jobs</span>
        </a>

        <a href="{{ route('account.myJobApplications') }}" class="menu-item {{ Request::is('account/my-jobs-applications') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Jobs Applied</span>
        </a>

        <a href="{{ route('account.savedJobs') }}" class="menu-item {{ Request::is('account/saved-jobs') ? 'active' : '' }}">
            <i class="fas fa-bookmark"></i>
            <span>Saved Jobs</span>
        </a>

        <!-- AI Features Section -->
        <div class="menu-divider"></div>
        <div class="menu-header">AI Features</div>

        <a href="{{ route('ai.resumeBuilder') }}" class="menu-item {{ Request::is('ai/resume-builder') ? 'active' : '' }}">
            <i class="fas fa-magic"></i>
            <span>AI Resume Builder</span>
        </a>

        <a href="{{ route('ai.jobMatch') }}" class="menu-item {{ Request::is('ai/job-match') ? 'active' : '' }}">
            <i class="fas fa-percentage"></i>
            <span>Job Match Score</span>
        </a>

        <div class="menu-divider"></div>

        <a href="{{ route('account.logout') }}" class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<style>
.modern-sidebar-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #fff;
}

.profile-quick-view {
    padding: 25px;
    text-align: center;
    border-bottom: 1px solid #e5e9f2;
}

.profile-image {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
}

.profile-image img {
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

.profile-info h5 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
}

.profile-info p {
    margin: 0;
    font-size: 13px;
    color: #64748b;
}

.sidebar-menu {
    padding: 20px 15px;
    flex: 1;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 10px;
    color: #64748b;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 5px;
}

.menu-item i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.menu-item span {
    font-size: 14px;
    font-weight: 500;
}

.menu-item:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.menu-item.active {
    background: #3b82f6;
    color: #fff;
}

.menu-header {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
    padding: 0.5rem 1rem;
    margin-top: 0.5rem;
}

.menu-divider {
    height: 1px;
    background: #e5e9f2;
    margin: 0.5rem 0;
}

@media (max-width: 1200px) {
    .profile-quick-view {
        padding: 15px;
    }

    .profile-image {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .profile-info {
        display: none;
    }

    .menu-item span {
        display: none;
    }

    .menu-item {
        justify-content: center;
        padding: 12px;
    }

    .menu-item i {
        margin: 0;
        font-size: 18px;
    }
}
</style>


