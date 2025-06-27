@extends('front.layouts.app')

@section('content')
<div class="jobs-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="text-white mb-4">Browse Available Jobs</h1>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-4 col-lg-3 mb-4">
            <form action="" method="post" name="searchForm" id="searchForm">
                <div class="filter-card">
                    <div class="mb-4">
                        <h5>Keywords</h5>
                        <input type="text" value="{{ Request::get('keyword') }}" name="keyword" id="keyword" placeholder="Keywords" class="search-input">
                    </div>

                    <div class="mb-4">
                        <h5>Location</h5>
                        <input type="text" value="{{ Request::get('location') }}" name="location" id="location" placeholder="Location" class="search-input">
                    </div>

                    <div class="mb-4">
                        <h5>Category</h5>
                        <select name="category" id="category" class="search-input">
                            <option value="">Select a Category</option>
                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <option {{ (Request::get('category') == $category->id) ? 'selected' : ''  }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-4">
                        <h5>Job Type</h5>
                        @if ($jobTypes->isNotEmpty())
                            <div class="job-type-filters">
                                @foreach ($jobTypes as $jobType)
                                    <div class="form-check custom-checkbox">
                                        <input {{ (in_array($jobType->id,$jobTypeArray) ? 'checked' : '') }} class="form-check-input" name="job_type" id="job-type-{{ $jobType->id }}" type="checkbox" value="{{ $jobType->id }}">
                                        <label class="form-check-label" for="job-type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Experience</h5>
                        <select name="experience" id="experience" class="search-input">
                            <option value="">Select Experience</option>
                            <option value="1" {{ (Request::get('experience') == 1) ? 'selected' : '' }}>1 Year</option>
                            <option value="2" {{ (Request::get('experience') == 2) ? 'selected' : '' }}>2 Years</option>
                            <option value="3" {{ (Request::get('experience') == 3) ? 'selected' : '' }}>3 Years</option>
                            <option value="4" {{ (Request::get('experience') == 4) ? 'selected' : '' }}>4 Years</option>
                            <option value="5" {{ (Request::get('experience') == 5) ? 'selected' : '' }}>5 Years</option>
                            <option value="6" {{ (Request::get('experience') == 6) ? 'selected' : '' }}>6 Years</option>
                            <option value="7" {{ (Request::get('experience') == 7) ? 'selected' : '' }}>7 Years</option>
                            <option value="8" {{ (Request::get('experience') == 8) ? 'selected' : '' }}>8 Years</option>
                            <option value="9" {{ (Request::get('experience') == 9) ? 'selected' : '' }}>9 Years</option>
                            <option value="10" {{ (Request::get('experience') == 10) ? 'selected' : '' }}>10 Years</option>
                            <option value="10_plus" {{ (Request::get('experience') == '10_plus') ? 'selected' : '' }}>10+ Years</option>
                        </select>
                    </div>

                    <div class="filter-buttons">
                        <button class="find-job-btn w-100 mb-2" type="submit">Search</button>
                        <a href="{{ route('jobs') }}" class="reset-btn w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Job Listings -->
        <div class="col-md-8 col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="results-count text-white">
                    {{ $jobs->total() }} jobs found
                </div>
                <div class="sort-dropdown">
                    <select name="sort" id="sort" class="search-input">
                        <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Latest</option>
                        <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                @if ($jobs->isNotEmpty())
                    @foreach ($jobs as $job)
                        <div class="col-lg-6">
                            <div class="job-card">
                                <div class="job-company-logo">
                                    <div class="company-badge" style="background: var(--primary-color);">
                                        {{ substr($job->company_name ?? 'C', 0, 1) }}
                                    </div>
                                </div>
                                <h3 class="job-title">{{ $job->title }}</h3>
                                <p class="company-name">{{ $job->company_name ?? 'Company Name' }}</p>
                                <p class="job-description">{{ Str::words(strip_tags($job->description), $words=15, '...') }}</p>
                                <div class="job-tags">
                                    <span class="tag">{{ $job->jobType->name }}</span>
                                    <span class="tag">{{ $job->location }}</span>
                                    @if (!is_null($job->salary))
                                        <span class="tag"><i class="fas fa-money-bill-wave"></i> {{ $job->salary }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('jobDetail', $job->id) }}" class="view-job-btn">View Details</a>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="col-12 mt-4">
                        <nav class="custom-pagination">
                            {{ $jobs->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                @else
                    <div class="col-12">
                        <div class="no-jobs-found">
                            <i class="fas fa-search mb-3"></i>
                            <h3>No jobs found</h3>
                            <p>Try adjusting your search filters or browse all available jobs.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.jobs-header {
    background: linear-gradient(135deg, #2B2B2B 0%, #1A1A1A 100%);
    padding: 3rem 0;
    margin-top: -2rem;
}

.filter-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 2rem;
    color: #fff;
}

.filter-card h5 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.search-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 0.8rem 1rem;
    border-radius: 10px;
    width: 100%;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.15);
}

.search-input option {
    background: #2B2B2B;
    color: #fff;
}

.job-type-filters {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.custom-checkbox label {
    color: rgba(255, 255, 255, 0.8);
    cursor: pointer;
}

.reset-btn {
    display: inline-block;
    width: 100%;
    padding: 0.8rem;
    background: transparent;
    color: var(--primary-color);
    text-align: center;
    border: 1px solid var(--primary-color);
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.reset-btn:hover {
    background: var(--primary-color);
    color: #fff;
}

.results-count {
    font-size: 1.1rem;
}

.no-jobs-found {
    text-align: center;
    padding: 3rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    color: #fff;
}

.no-jobs-found i {
    font-size: 3rem;
    color: var(--primary-color);
    display: block;
}

.no-jobs-found h3 {
    margin-bottom: 1rem;
}

.no-jobs-found p {
    color: rgba(255, 255, 255, 0.7);
}

.custom-pagination .pagination {
    justify-content: center;
    gap: 0.5rem;
}

.custom-pagination .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.custom-pagination .page-link:hover,
.custom-pagination .page-item.active .page-link {
    background: var(--primary-color);
    color: #fff;
}

.job-description {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1rem;
    line-height: 1.6;
}
</style>

@endsection

@section('customJs')
<script type="text/javascript">
    $("#searchForm").submit(function (e) {
        e.preventDefault();

        var url = '{{ route("jobs") }}?';

        var keywords = $("#keyword").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();
        var sort = $("#sort").val();

        var checkedJobTypes = $("input:checkbox[name='job_type']:checked").map(function(){
            return $(this).val();
        }).get();

        if(keywords != "") url += '&keyword='+keywords;
        if(location != "") url += '&location='+location;
        if(category != "") url += '&category='+category;
        if(experience != "") url += '&experience='+experience;
        if(checkedJobTypes.length > 0) url += '&jobType='+checkedJobTypes;
        url += '&sort='+sort;

        window.location.href=url;
    });

    $("#sort").change(function (e) {
        $("#searchForm").submit();
    });
</script>
@endsection
