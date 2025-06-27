@extends('front.layouts.app')

@section('content')
<div class="job-detail-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('jobs') }}" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                </a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="company-badge me-4" style="background: var(--primary-color);">
                        {{ substr($job->company_name ?? 'C', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="job-title mb-2">{{ $job->title }}</h1>
                        <p class="company-name mb-0">{{ $job->company_name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                @if (Auth::check())
                    <button onclick="saveJob({{ $job->id }})" class="save-job-btn me-2 {{ ($count==1) ? 'active' : '' }}">
                        <i class="fas {{ ($count==1) ? 'fa-heart' : 'fa-heart' }}"></i>
                        {{ ($count==1) ? 'Saved' : 'Save Job' }}
                    </button>
                    <button onclick="applyJob({{ $job->id }})" class="apply-job-btn">
                        <i class="fas fa-paper-plane me-2"></i>Apply Now
                    </button>
                @else
                    <a href="{{ route('account.login') }}" class="save-job-btn me-2">
                        <i class="fas fa-heart"></i>Save Job
                    </a>
                    <a href="{{ route('account.login') }}" class="apply-job-btn">
                        <i class="fas fa-paper-plane me-2"></i>Apply Now
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Job Details -->
            <div class="content-card mb-4">
                @include('front.message')

                <div class="job-tags mb-4">
                    <span class="tag">
                        <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                    </span>
                    <span class="tag">
                        <i class="fas fa-clock"></i> {{ $job->jobType->name }}
                    </span>
                    @if (!is_null($job->salary))
                        <span class="tag">
                            <i class="fas fa-money-bill-wave"></i> {{ $job->salary }}
                        </span>
                    @endif
                    <span class="tag">
                        <i class="fas fa-calendar"></i> Posted {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                    </span>
                </div>

                <div class="content-section">
                    <h3>Job Description</h3>
                    <div class="content-text">
                        {!! nl2br($job->description) !!}
                    </div>
                </div>

                @if (!empty($job->responsibility))
                    <div class="content-section">
                        <h3>Responsibilities</h3>
                        <div class="content-text">
                            {!! nl2br($job->responsibility) !!}
                        </div>
                    </div>
                @endif

                @if (!empty($job->qualifications))
                    <div class="content-section">
                        <h3>Qualifications</h3>
                        <div class="content-text">
                            {!! nl2br($job->qualifications) !!}
                        </div>
                    </div>
                @endif

                @if (!empty($job->benefits))
                    <div class="content-section">
                        <h3>Benefits</h3>
                        <div class="content-text">
                            {!! nl2br($job->benefits) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Applicants Section -->
            @if (Auth::user() && Auth::user()->id == $job->user_id)
                <div class="content-card">
                    <h3 class="mb-4">Applicants</h3>
                    @if ($applications->isNotEmpty())
                        <div class="applicants-list">
                            @foreach ($applications as $application)
                                <div class="applicant-item">
                                    <div class="applicant-info">
                                        <h4>{{ $application->user->name }}</h4>
                                        <p class="mb-0">{{ $application->user->email }}</p>
                                        <p class="mb-0">{{ $application->user->mobile }}</p>
                                    </div>
                                    <div class="applicant-date">
                                        {{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-applicants">
                            <i class="fas fa-users mb-3"></i>
                            <p>No applicants yet</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Job Summary -->
            <div class="content-card mb-4">
                <h3>Job Summary</h3>
                <div class="summary-list">
                    <div class="summary-item">
                        <span class="label">Published on</span>
                        <span class="value">{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Vacancy</span>
                        <span class="value">{{ $job->vacancy }} Position(s)</span>
                    </div>
                    @if (!empty($job->salary))
                        <div class="summary-item">
                            <span class="label">Salary</span>
                            <span class="value">{{ $job->salary }}</span>
                        </div>
                    @endif
                    <div class="summary-item">
                        <span class="label">Location</span>
                        <span class="value">{{ $job->location }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Job Type</span>
                        <span class="value">{{ $job->jobType->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Company Details -->
            <div class="content-card">
                <h3>Company Details</h3>
                <div class="summary-list">
                    <div class="summary-item">
                        <span class="label">Name</span>
                        <span class="value">{{ $job->company_name }}</span>
                    </div>
                    @if (!empty($job->company_location))
                        <div class="summary-item">
                            <span class="label">Location</span>
                            <span class="value">{{ $job->company_location }}</span>
                        </div>
                    @endif
                    @if (!empty($job->company_website))
                        <div class="summary-item">
                            <span class="label">Website</span>
                            <a href="{{ $job->company_website }}" target="_blank" class="website-link">
                                {{ $job->company_website }}
                                <i class="fas fa-external-link-alt ms-2"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-detail-header {
    background: linear-gradient(135deg, #2B2B2B 0%, #1A1A1A 100%);
    padding: 3rem 0;
    margin-top: -2rem;
    color: #fff;
}

.back-link {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: var(--primary-color);
}

.company-badge {
    width: 80px;
    height: 80px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
    color: #fff;
}

.job-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
}

.company-name {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.7);
}

.save-job-btn,
.apply-job-btn {
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.save-job-btn {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.save-job-btn:hover,
.save-job-btn.active {
    background: var(--primary-color);
    color: #fff;
}

.apply-job-btn {
    background: var(--primary-color);
    color: #fff;
}

.apply-job-btn:hover {
    background: var(--secondary-color);
    color: #fff;
}

.content-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 2rem;
    color: #fff;
}

.content-section {
    margin-bottom: 2rem;
}

.content-section:last-child {
    margin-bottom: 0;
}

.content-section h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.content-text {
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
}

.summary-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.summary-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.summary-item .label {
    color: rgba(255, 255, 255, 0.7);
}

.summary-item .value {
    color: #fff;
    font-weight: 500;
}

.website-link {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.website-link:hover {
    color: var(--secondary-color);
}

.applicants-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.applicant-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.applicant-info h4 {
    margin-bottom: 0.5rem;
    color: #fff;
}

.applicant-info p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.applicant-date {
    color: var(--primary-color);
    font-weight: 500;
}

.no-applicants {
    text-align: center;
    padding: 2rem;
}

.no-applicants i {
    font-size: 3rem;
    color: var(--primary-color);
    display: block;
}

.no-applicants p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}
</style>

@endsection

@section('customJs')
<script>
function saveJob(jobId) {
    $.ajax({
        url: '{{ route("account.saveJob") }}',
        type: 'post',
        data: {jobId: jobId},
        dataType: 'json',
        success: function(response) {
            window.location.href = window.location.href;
        }
    });
}

function applyJob(jobId) {
    $.ajax({
        url: '{{ route("account.applyJob") }}',
        type: 'post',
        data: {jobId: jobId},
        dataType: 'json',
        success: function(response) {
            window.location.href = window.location.href;
        }
    });
}
</script>
@endsection
