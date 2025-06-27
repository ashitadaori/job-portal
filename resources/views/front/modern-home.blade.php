@extends('front.layouts.app')

@section('content')
<div class="hero-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="hero-title display-3 text-white mb-4">Find Your Dream Job Today</h1>
                <p class="hero-subtitle text-white-50 mb-5">Discover opportunities that match your skills and aspirations</p>
                
                <div class="search-box bg-white p-4 rounded-4 shadow mb-4">
                    <form action="{{ route('jobs') }}" method="GET" class="search-form">
                        <div class="row g-3">
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-transparent">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="keyword" class="form-control border-0 shadow-none" placeholder="Job title or keyword">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-transparent">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                    </span>
                                    <input type="text" name="location" class="form-control border-0 shadow-none" placeholder="Location">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-success w-100 search-btn">
                                    Search Jobs
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="trending-keywords">
                    <span class="text-white-50 me-2">Trending:</span>
                    <div class="d-inline-flex gap-2">
                        <a href="{{ route('jobs', ['keyword' => 'Remote']) }}" class="badge rounded-pill bg-light bg-opacity-25 text-white text-decoration-none">Remote</a>
                        <a href="{{ route('jobs', ['jobType' => 'Full-time']) }}" class="badge rounded-pill bg-light bg-opacity-25 text-white text-decoration-none">Full-time</a>
                        <a href="{{ route('jobs', ['jobType' => 'Part-time']) }}" class="badge rounded-pill bg-light bg-opacity-25 text-white text-decoration-none">Part-time</a>
                        <a href="{{ route('jobs', ['keyword' => 'Developer']) }}" class="badge rounded-pill bg-light bg-opacity-25 text-white text-decoration-none">Developer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    min-height: 600px;
    display: flex;
    align-items: center;
    position: relative;
}

.hero-title {
    font-weight: 700;
}

.search-box {
    background: rgba(255, 255, 255, 1) !important;
}

.search-box .input-group {
    border-right: 1px solid #eee;
}

.search-box .col-lg-2 .input-group {
    border-right: none;
}

.search-btn {
    background-color: #82CD47;
    border: none;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background-color: #6fb83d;
}

.trending-keywords .badge {
    transition: all 0.3s ease;
}

.trending-keywords .badge:hover {
    background-color: rgba(255, 255, 255, 0.4) !important;
}

@media (max-width: 991.98px) {
    .search-box .input-group {
        border-right: none;
    }
}
</style>

@if($featuredJobs->isNotEmpty())
<div class="featured-jobs py-5">
    <div class="container">
        <h2 class="text-white mb-4">Featured Jobs</h2>
        <div class="row">
            @foreach($featuredJobs as $job)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="company-logo me-3 rounded-3 p-2" style="background-color: {{ '#' . substr(md5($job->company_name ?? 'Company'), 0, 6) }}">
                                <span class="text-white">{{ strtoupper(substr($job->company_name ?? 'C', 0, 1)) }}</span>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">{{ $job->title }}</h5>
                                <p class="text-muted mb-0 small">{{ $job->company_name }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>{{ $job->location }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-clock me-2"></i>
                                <span>{{ $job->jobType->name }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('jobDetail', $job->id) }}" class="btn btn-outline-success w-100">Apply Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
