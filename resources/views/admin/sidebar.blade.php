<!-- Modern Admin Sidebar -->
<div class="modern-sidebar-content">
    <!-- Admin Profile -->
    <div class="admin-profile">
        <div class="profile-image">
            @if (Auth::user()->image != '')
                <img src="{{ asset('profile_img/thumb/'.Auth::user()->image) }}" alt="Admin">
            @else
                <img src="{{ asset('assets/images/avatar7.png') }}" alt="Admin">
            @endif
        </div>
        <div class="profile-info">
            <h5>{{ Auth::user()->name }}</h5>
            <span class="badge bg-primary">Administrator</span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu">
        <div class="menu-section">
            <span class="menu-header">MAIN</span>
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">JOB MANAGEMENT</span>
            <a href="{{ route('admin.jobs.index') }}" class="menu-item {{ Request::is('admin/jobs*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span>Job Posts</span>
            </a>
            <a href="{{ route('admin.applications.index') }}" class="menu-item {{ Request::is('admin/applications*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Applications</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Categories & Tags</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">USER MANAGEMENT</span>
            <a href="{{ route('admin.employers.index') }}" class="menu-item {{ Request::is('admin/employers*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Employers</span>
            </a>
            <a href="{{ route('admin.jobseekers.index') }}" class="menu-item {{ Request::is('admin/jobseekers*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Job Seekers</span>
            </a>
            <a href="{{ route('admin.resumes.index') }}" class="menu-item {{ Request::is('admin/resumes*') ? 'active' : '' }}">
                <i class="fas fa-file-pdf"></i>
                <span>Resume Database</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">ANALYTICS</span>
            <a href="{{ route('admin.reports.index') }}" class="menu-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Reports & Statistics</span>
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="menu-item {{ Request::is('admin/analytics*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Job Analytics</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">CONTENT</span>
            <a href="{{ route('admin.pages.index') }}" class="menu-item {{ Request::is('admin/pages*') ? 'active' : '' }}">
                <i class="fas fa-file"></i>
                <span>Static Pages</span>
            </a>
            <a href="{{ route('admin.blog.index') }}" class="menu-item {{ Request::is('admin/blog*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Blog/News</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="menu-item {{ Request::is('admin/faqs*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i>
                <span>FAQs</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">COMMUNICATION</span>
            <a href="{{ route('admin.announcements.index') }}" class="menu-item {{ Request::is('admin/announcements*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="menu-item {{ Request::is('admin/messages*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="menu-item {{ Request::is('admin/notifications*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">SETTINGS</span>
            <a href="{{ route('admin.settings.general') }}" class="menu-item {{ Request::is('admin/settings/general*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>General Settings</span>
            </a>
            <a href="{{ route('admin.settings.email') }}" class="menu-item {{ Request::is('admin/settings/email*') ? 'active' : '' }}">
                <i class="fas fa-envelope-open"></i>
                <span>Email Templates</span>
            </a>
            <a href="{{ route('admin.settings.seo') }}" class="menu-item {{ Request::is('admin/settings/seo*') ? 'active' : '' }}">
                <i class="fas fa-search"></i>
                <span>SEO Settings</span>
            </a>
        </div>

        <div class="menu-section">
            <span class="menu-header">ADMINISTRATION</span>
            <a href="{{ route('admin.roles.index') }}" class="menu-item {{ Request::is('admin/roles*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Roles & Permissions</span>
            </a>
            <a href="{{ route('admin.audit.index') }}" class="menu-item {{ Request::is('admin/audit*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Audit Logs</span>
            </a>
            <a href="{{ route('admin.security.index') }}" class="menu-item {{ Request::is('admin/security*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span>Security</span>
            </a>
        </div>
    </div>
</div>

<style>
.modern-sidebar-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #fff;
}

.admin-profile {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    border-bottom: 1px solid #e5e9f2;
}

.profile-image {
    width: 50px;
    height: 50px;
}

.profile-image img {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
}

.profile-info h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.profile-info .badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}

.sidebar-menu {
    padding: 15px;
    flex: 1;
    overflow-y: auto;
}

.menu-section {
    margin-bottom: 20px;
}

.menu-header {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 12px;
    margin-bottom: 8px;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    color: #475569;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 2px;
}

.menu-item i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.menu-item span {
    font-size: 13px;
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

@media (max-width: 1200px) {
    .profile-info {
        display: none;
    }

    .menu-header {
        text-align: center;
        padding: 0;
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


