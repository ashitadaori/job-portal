@extends('admin.sidebar')
@section('title', 'Labor Market Analytics')
@section('content')

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Labor Market Analytics Dashboard</h1>
    
    <!-- Dashboard Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJobs }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalApplications }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCategories }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Job Categories Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Job Categories Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="jobCategoriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Types Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Job Types Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="jobTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location and Experience Charts -->
    <div class="row">
        <!-- Locations Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Job Locations</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="locationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience Levels Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Experience Levels</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="experienceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Trends Chart -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Application Trends</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="applicationTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cluster Analysis -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cluster Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>User Clusters</h5>
                            <p>Total clusters: {{ count($insights['user_clusters'] ?? []) }}</p>
                            <a href="{{ route('admin.analytics.userClusters') }}" class="btn btn-primary btn-sm">View User Clusters</a>
                        </div>
                        <div class="col-md-6">
                            <h5>Job Clusters</h5>
                            <p>Total clusters: {{ count($insights['job_clusters'] ?? []) }}</p>
                            <a href="{{ route('admin.analytics.jobClusters') }}" class="btn btn-primary btn-sm">View Job Clusters</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Parse data from PHP
const jobCategories = @json($insights['job_categories'] ?? []);
const jobTypes = @json($insights['job_types'] ?? []);
const locations = @json($insights['locations'] ?? []);
const experienceLevels = @json($insights['experience_levels'] ?? []);
const applicationTrends = @json($insights['application_trends'] ?? []);

// Function to generate random colors
function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const r = Math.floor(Math.random() * 255);
        const g = Math.floor(Math.random() * 255);
        const b = Math.floor(Math.random() * 255);
        colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
    }
    return colors;
}

// Job Categories Chart
if (jobCategories.length > 0) {
    const categoryLabels = jobCategories.map(item => item.name);
    const categoryData = jobCategories.map(item => item.total);
    const categoryColors = generateColors(categoryLabels.length);
    
    new Chart(document.getElementById('jobCategoriesChart'), {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: categoryColors,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// Job Types Chart
if (jobTypes.length > 0) {
    const typeLabels = jobTypes.map(item => item.name);
    const typeData = jobTypes.map(item => item.total);
    const typeColors = generateColors(typeLabels.length);
    
    new Chart(document.getElementById('jobTypesChart'), {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeData,
                backgroundColor: typeColors,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// Locations Chart
if (locations.length > 0) {
    const locationLabels = locations.map(item => item.location);
    const locationData = locations.map(item => item.total);
    const locationColors = generateColors(locationLabels.length);
    
    new Chart(document.getElementById('locationsChart'), {
        type: 'bar',
        data: {
            labels: locationLabels,
            datasets: [{
                label: 'Number of Jobs',
                data: locationData,
                backgroundColor: locationColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Experience Levels Chart
if (experienceLevels.length > 0) {
    const expLabels = experienceLevels.map(item => item.experience + ' years');
    const expData = experienceLevels.map(item => item.total);
    const expColors = generateColors(expLabels.length);
    
    new Chart(document.getElementById('experienceChart'), {
        type: 'bar',
        data: {
            labels: expLabels,
            datasets: [{
                label: 'Number of Jobs',
                data: expData,
                backgroundColor: expColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Application Trends Chart
if (applicationTrends.length > 0) {
    const trendLabels = applicationTrends.map(item => item.date);
    const trendData = applicationTrends.map(item => item.total);
    
    new Chart(document.getElementById('applicationTrendsChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Applications',
                data: trendData,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endsection