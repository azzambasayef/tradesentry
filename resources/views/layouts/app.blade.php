<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeSentry - @yield('title', 'Dashboard')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0A84FF;
            --dark-navy: #0F172A;
            --dark-bg: #0A0F1C;
            --card-bg: #111827;
            --text-light: #F8FAFC;
            --text-muted: #CBD5E1;
            --accent-blue: #38BDF8;
        }
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
        }
        
        /* Navbar Styling */
        .navbar {
            background-color: rgba(15, 23, 42, 0.92) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #1e293b;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            z-index: 1050;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.4rem;
        }
        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            margin: 0 4px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-blue) !important;
            background-color: rgba(10, 132, 255, 0.1);
        }
        
        /* Global Search Bar di Navbar */
        .global-search {
            background-color: rgba(10, 15, 28, 0.8);
            border: 1px solid #1e293b;
            color: var(--text-light);
            border-radius: 20px;
            padding: 5px 15px 5px 35px;
            width: 300px;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .global-search:focus {
            background-color: var(--card-bg);
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(10, 132, 255, 0.25);
            outline: none;
            color: white;
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* General Card */
        .card {
            background-color: var(--card-bg);
            border: 1px solid #1e293b;
            border-radius: 12px;
        }
        .text-primary-blue { color: var(--primary-blue) !important; }
        .text-accent { color: var(--accent-blue) !important; }
    </style>
</head>
<body>

@auth
<nav class="navbar navbar-expand-lg sticky-top px-4 py-2 mb-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand text-light" href="{{ route('dashboard') }}">
            <i class="fas fa-anchor text-primary-blue me-2"></i>TradeSentry
        </a>
        
        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar">
            <i class="fas fa-bars text-light"></i>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-map-marked-alt me-1"></i> Live Map
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}" href="{{ route('countries.index') }}">
                        <i class="fas fa-globe me-1"></i> Countries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="opacity: 0.5; cursor: not-allowed;" title="Coming soon">
                        <i class="fas fa-cloud-sun-rain me-1"></i> Weather
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="opacity: 0.5; cursor: not-allowed;" title="Coming soon">
                        <i class="fas fa-chart-line me-1"></i> Risk Engine
                    </a>
                </li>
            </ul>

            <!-- Right Menu (Search, Clock, Profile) -->
            <div class="d-flex align-items-center gap-3">
                <!-- Search Bar -->
                <div class="position-relative d-none d-xl-block">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control global-search" placeholder="Search countries, ports, ships...">
                </div>

                <!-- Clock -->
                <div class="text-muted d-none d-lg-flex align-items-center border-start border-secondary ps-3 ms-2 text-nowrap">
                    <i class="far fa-clock text-accent me-1"></i> <span id="utc-clock">00:00:00 UTC</span>
                </div>

                <!-- Notifications -->
                <a href="#" class="text-light position-relative text-decoration-none mx-2">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
                </a>

                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <a href="#" class="text-light text-decoration-none fw-medium dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="bg-secondary rounded-circle d-inline-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px; background-color: var(--primary-blue) !important;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow" style="background-color: var(--card-bg); border-color: #1e293b;">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="px-2">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger rounded"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
@endauth

<div class="container-fluid px-4 pb-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Real-time WIB Clock update
    const updateClock = () => {
        const clockElement = document.getElementById('utc-clock');
        if (clockElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false });
            clockElement.textContent = timeString + ' WIB';
        }
    };
    setInterval(updateClock, 1000);
    updateClock();
</script>
</body>
</html>
