<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TUGMAJOBS - Find Your Dream Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #82CD47;
            --secondary-color: #1e2a78;
        }

        .navbar {
            background-color: #1a1e21 !important;
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Add new hover dropdown styles */
        @media (min-width: 992px) {
            .navbar .nav-item:hover .dropdown-menu {
                display: block;
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .navbar .dropdown-menu {
                display: block;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all 0.3s ease;
                margin-top: 0;
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .navbar .dropdown-item {
                padding: 8px 20px;
                transition: all 0.3s ease;
            }

            .navbar .dropdown-item:hover {
                background-color: var(--primary-color);
                color: #fff;
                transform: translateX(5px);
            }
        }

        .navbar-toggler {
            border-color: rgba(255,255,255,0.1);
        }

        .login-btn {
            background-color: var(--primary-color);
            color: #fff !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px;
            font-weight: 600;
        }

        .login-btn:hover {
            background-color: #6fb83d;
        }

        .dropdown-menu {
            background-color: #1a1e21;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-custom {
            background: linear-gradient(135deg, #1e2a78 0%, #ff6b6b 100%);
            min-height: 100vh;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">TUGMAJOBS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($categories ?? [] as $category)
                                <li><a class="dropdown-item" href="{{ route('jobs', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="homeDropdown" role="button" data-bs-toggle="dropdown">
                            Home
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="#">Latest Jobs</a></li>
                            <li><a class="dropdown-item" href="#">Featured Companies</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            About us
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Our Story</a></li>
                            <li><a class="dropdown-item" href="#">Mission & Vision</a></li>
                            <li><a class="dropdown-item" href="#">Team</a></li>
                            <li><a class="dropdown-item" href="#">Contact Us</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profilingDropdown" role="button" data-bs-toggle="dropdown">
                            Profiling
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('ai.resumeBuilder') }}">AI Resume Builder</a></li>
                            <li><a class="dropdown-item" href="{{ route('ai.jobMatch') }}">AI Job Match</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Skills Assessment</a></li>
                            <li><a class="dropdown-item" href="#">Career Path</a></li>
                            <li><a class="dropdown-item" href="#">Job Recommendations</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="jobseekersDropdown" role="button" data-bs-toggle="dropdown">
                            Jobseekers
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Job Search</a></li>
                            <li><a class="dropdown-item" href="#">Career Resources</a></li>
                            <li><a class="dropdown-item" href="#">Interview Tips</a></li>
                            <li><a class="dropdown-item" href="#">Salary Guide</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="browseJobsDropdown" role="button" data-bs-toggle="dropdown">
                            Browse jobs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('jobs') }}">All Jobs</a></li>
                            <li><a class="dropdown-item" href="#">Featured Jobs</a></li>
                            <li><a class="dropdown-item" href="#">Remote Jobs</a></li>
                            <li><a class="dropdown-item" href="#">Full Time</a></li>
                            <li><a class="dropdown-item" href="#">Part Time</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    @auth
                        <a href="{{ route('account.profile') }}" class="nav-link">My Account</a>
                        <a href="{{ route('account.logout') }}" class="nav-link">Logout</a>
                    @else
                        <a href="{{ route('account.login') }}" class="nav-link login-btn">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="gradient-custom">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
