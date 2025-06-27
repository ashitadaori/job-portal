@extends('front.layouts.app')

@section('content')
<div class="modern-admin-dashboard">
    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        @include('admin.sidebar')
    </div>

    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-left">
                <h4 class="mb-0">Dashboard Overview</h4>
                <div class="breadcrumb">
                    <span>Welcome back, {{ Auth::user()->name }}</span>
                </div>
            </div>
            <div class="header-right">
                <div class="date-filter">
                    <select class="form-select">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <!-- Jobs Stats -->
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalJobs ?? 0 }}</h3>
                    <p>Total Jobs</p>
                    <div class="stat-meta">
                        <span class="trend up">
                            <i class="fas fa-arrow-up"></i> 12%
                        </span>
                        <span class="period">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Applications Stats -->
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalApplications ?? 0 }}</h3>
                    <p>Applications</p>
                    <div class="stat-meta">
                        <span class="trend up">
                            <i class="fas fa-arrow-up"></i> 8%
                        </span>
                        <span class="period">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Employers Stats -->
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalEmployers ?? 0 }}</h3>
                    <p>Employers</p>
                    <div class="stat-meta">
                        <span class="trend up">
                            <i class="fas fa-arrow-up"></i> 5%
                        </span>
                        <span class="period">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Job Seekers Stats -->
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalJobSeekers ?? 0 }}</h3>
                    <p>Job Seekers</p>
                    <div class="stat-meta">
                        <span class="trend up">
                            <i class="fas fa-arrow-up"></i> 15%
                        </span>
                        <span class="period">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Jobs -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Recent Job Posts</h5>
                    <div class="header-actions">
                        <button class="btn btn-light" onclick="window.location.href='{{ route('admin.jobs.index') }}'">
                            View All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJobs ?? [] as $job)
                                <tr data-job-id="{{ $job->id }}">
                                    <td>
                                        <div class="job-title">{{ $job->title }}</div>
                                        <div class="job-meta">Posted {{ $job->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>{{ $job->company_name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $job->applications_count }} applied</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $job->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-light">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-light" onclick="deleteJob({{ $job->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent jobs found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Recent Applications</h5>
                    <div class="header-actions">
                        <button class="btn btn-light" onclick="window.location.href='{{ route('admin.applications.index') }}'">
                            View All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Job</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications ?? [] as $application)
                                <tr data-application-id="{{ $application->id }}">
                                    <td>
                                        <div class="applicant-info">
                                            <div class="applicant-name">{{ $application->user->name }}</div>
                                            <div class="applicant-email">{{ $application->user->email }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $application->job->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $application->status_color }} status-badge">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-light" onclick="updateStatus({{ $application->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent applications found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Analytics Charts -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Job Analytics</h5>
                    <div class="header-actions">
                        <button class="btn btn-light" onclick="window.location.href='{{ route('admin.analytics.index') }}'">
                            View Details
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="jobAnalytics" height="300"></canvas>
                    </div>
                </div>

            <!-- User Activity -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Recent Activity</h5>
                    <div class="header-actions">
                        <button class="btn btn-light" onclick="window.location.href='{{ route('admin.audit.index') }}'">
                            View All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-{{ $activity->icon }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-text">{{ $activity->description }}</div>
                                <div class="activity-meta">
                                    <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    <span class="activity-user">by {{ $activity->user->name }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center">No recent activity found</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-admin-dashboard {
    display: flex;
    min-height: 100vh;
    background: #f8fafc;
}

.dashboard-sidebar {
    width: 280px;
    background: #fff;
    border-right: 1px solid #e5e9f2;
    position: fixed;
    height: 100vh;
    left: 0;
    top: 0;
    z-index: 100;
}

.dashboard-main {
    flex: 1;
    margin-left: 280px;
    padding: 20px 30px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.header-left h4 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
}

.breadcrumb {
    margin-top: 5px;
    font-size: 14px;
    color: #64748b;
}

.date-filter select {
    border: 1px solid #e5e9f2;
    border-radius: 10px;
    padding: 8px 15px;
    font-size: 14px;
    color: #1e293b;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.stat-card.primary { border-left: 4px solid #3b82f6; }
.stat-card.success { border-left: 4px solid #22c55e; }
.stat-card.warning { border-left: 4px solid #f59e0b; }
.stat-card.info { border-left: 4px solid #06b6d4; }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-card.primary .stat-icon { background: #eff6ff; color: #3b82f6; }
.stat-card.success .stat-icon { background: #f0fdf4; color: #22c55e; }
.stat-card.warning .stat-icon { background: #fffbeb; color: #f59e0b; }
.stat-card.info .stat-icon { background: #ecfeff; color: #06b6d4; }

.stat-details h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #1e293b;
}

.stat-details p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #64748b;
}

.stat-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    font-size: 12px;
}

.trend {
    display: flex;
    align-items: center;
    gap: 4px;
}

.trend.up { color: #22c55e; }
.trend.down { color: #ef4444; }

.period {
    color: #94a3b8;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
}

.content-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e9f2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.card-body {
    padding: 20px;
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px;
    border-bottom: 1px solid #e5e9f2;
}

.table td {
    padding: 12px;
    font-size: 14px;
    color: #1e293b;
    border-bottom: 1px solid #e5e9f2;
}

.job-title {
    font-weight: 500;
    color: #1e293b;
}

.job-meta {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

.badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-light {
    background: #f8fafc;
    border: 1px solid #e5e9f2;
    color: #64748b;
}

.btn-light:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #f8fafc;
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #eff6ff;
    color: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.activity-details {
    flex: 1;
}

.activity-text {
    font-size: 14px;
    color: #1e293b;
    margin-bottom: 4px;
}

.activity-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #64748b;
}

@media (max-width: 1200px) {
    .dashboard-sidebar {
        width: 80px;
    }
    
    .dashboard-main {
        margin-left: 80px;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>

@endsection

@section('customJs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Job Analytics Chart
const ctx = document.getElementById('jobAnalytics').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($monthlyStats['months']),
        datasets: [{
            label: 'Job Posts',
            data: @json($monthlyStats['jobs']),
            borderColor: '#3b82f6',
            tension: 0.4,
            fill: false
        }, {
            label: 'Applications',
            data: @json($monthlyStats['applications']),
            borderColor: '#22c55e',
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function deleteJob(id) {
    if(confirm('Are you sure you want to delete this job?')) {
        fetch(`/admin/jobs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Remove the row from the table
                document.querySelector(`tr[data-job-id="${id}"]`).remove();
                // Show success message
                alert('Job deleted successfully');
            } else {
                alert('Error deleting job');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting job');
        });
    }
}

function updateStatus(id) {
    const statuses = ['pending', 'reviewing', 'accepted', 'rejected'];
    const currentStatus = document.querySelector(`tr[data-application-id="${id}"] .status-badge`).textContent.toLowerCase();
    const currentIndex = statuses.indexOf(currentStatus);
    const nextStatus = statuses[(currentIndex + 1) % statuses.length];

    fetch(`/admin/applications/${id}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: nextStatus })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Update the status badge
            const badge = document.querySelector(`tr[data-application-id="${id}"] .status-badge`);
            badge.textContent = nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1);
            badge.className = `badge bg-${getStatusColor(nextStatus)} status-badge`;
        } else {
            alert('Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'reviewing': 'info',
        'accepted': 'success',
        'rejected': 'danger'
    };
    return colors[status] || 'secondary';
}
</script>
@endsection
