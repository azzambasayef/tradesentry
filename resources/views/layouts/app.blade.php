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
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #1e1e1e;
            border-right: 1px solid #333;
            padding-top: 20px;
        }
        .sidebar a {
            color: #b3b3b3;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            font-weight: 500;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #00ffcc;
            background-color: #2a2a2a;
            border-left: 4px solid #00ffcc;
        }
        .navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333;
        }
        .card {
            background-color: #1e1e1e;
            border: 1px solid #333;
            border-radius: 10px;
        }
        .card-header {
            border-bottom: 1px solid #333;
            background-color: #232323;
            font-weight: 600;
        }
        .text-neon {
            color: #00ffcc;
        }
        .btn-neon {
            background-color: #00ffcc;
            color: #121212;
            border: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-neon:hover {
            background-color: #00ccaa;
            color: #121212;
            box-shadow: 0 0 10px #00ffcc;
        }
        .mini-widget {
            background-color: #2a2a2a;
            border-radius: 8px;
            padding: 10px;
            margin: 15px;
            font-size: 0.85rem;
            text-align: center;
            border: 1px solid #444;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        @auth
        <div class="col-md-2 d-none d-md-block sidebar px-0 position-fixed">
            <h3 class="text-center text-neon fw-bold mb-4">TradeSentry</h3>
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('countries.index') }}" class="{{ request()->routeIs('countries.*') ? 'active' : '' }}">
                <i class="fas fa-globe me-2"></i> Countries
            </a>
            <a href="#" class="text-muted" style="cursor:not-allowed" title="Coming soon in Phase 4">
                <i class="fas fa-cloud-sun-rain me-2"></i> Weather
            </a>
            <a href="#" class="text-muted" style="cursor:not-allowed" title="Coming soon in Phase 6">
                <i class="fas fa-chart-line me-2"></i> Risk Engine
            </a>

            <!-- Mini Currency/Map Placeholder -->
            <div class="mini-widget mt-5">
                <div class="text-neon mb-1"><i class="fas fa-exchange-alt"></i> Live Rate</div>
                <div>USD to IDR: <br> <span class="fw-bold">Waiting API...</span></div>
            </div>
            <div class="mini-widget mt-2">
                <div class="text-neon mb-1"><i class="fas fa-map-marker-alt"></i> Global Map</div>
                <div style="height: 60px; background: #333; border-radius: 4px; display:flex; align-items:center; justify-content:center;">
                    <small>Leaflet Pending</small>
                </div>
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <div class="@auth col-md-10 offset-md-2 @else col-12 @endauth p-0">
            @auth
            <!-- Topbar -->
            <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 d-md-none text-neon fw-bold">TradeSentry</span>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="text-light me-3"><i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </nav>
            @endauth

            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
