@extends('layouts.app')
@section('title', 'API Documentation - TradeSentry')

@section('content')
<div class="row h-100">
    <!-- Sidebar / Nav -->
    <div class="col-md-3 border-end border-secondary pb-5">
        <h4 class="text-light mb-4"><i class="fas fa-book-open me-2 text-primary-blue"></i> API Reference</h4>
        
        <div class="list-group list-group-flush bg-transparent">
            <a href="#api-countries" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-success me-2">GET</span> /api/countries
            </a>
            <a href="#api-ports" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-success me-2">GET</span> /api/ports
            </a>
            <a href="#api-ships" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-success me-2">GET</span> /api/ships
            </a>
            <a href="#api-compare" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-warning text-dark me-2">POST</span> /api/compare
            </a>
            <a href="#api-risk" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-warning text-dark me-2">POST</span> /risk/recalculate
            </a>
            <a href="#api-news" class="list-group-item list-group-item-action bg-transparent text-light border-secondary">
                <span class="badge bg-warning text-dark me-2">POST</span> /news/fetch
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="col-md-9 pt-2 pb-5" style="max-height: 85vh; overflow-y: auto;">
        
        <div class="mb-5" id="api-countries">
            <h3 class="text-light border-bottom border-secondary pb-2">Get Countries List</h3>
            <p class="text-muted">Fetches all monitored countries along with their calculated Risk Scores and basic economic data.</p>
            <div class="bg-dark p-3 rounded border border-secondary mb-3 d-flex align-items-center">
                <span class="badge bg-success fs-6 me-3">GET</span> 
                <code class="fs-5 text-light">/api/countries</code>
            </div>
            <h5 class="text-light mt-4">Example Response</h5>
            <pre class="bg-dark text-success p-3 rounded border border-secondary"><code>[
  {
    "id": 1,
    "name": "Japan",
    "code": "JP",
    "region": "Asia",
    "lat": "36.2048",
    "lng": "138.2529",
    "risk_score": {
      "composite_score": "35.40",
      "risk_level": "Low"
    }
  }
]</code></pre>
        </div>

        <div class="mb-5" id="api-ports">
            <h3 class="text-light border-bottom border-secondary pb-2">Get Port Datasets</h3>
            <p class="text-muted">Fetches all registered port coordinates and their associated country's risk score for map plotting.</p>
            <div class="bg-dark p-3 rounded border border-secondary mb-3 d-flex align-items-center">
                <span class="badge bg-success fs-6 me-3">GET</span> 
                <code class="fs-5 text-light">/api/ports</code>
            </div>
            <h5 class="text-light mt-4">Example Response</h5>
            <pre class="bg-dark text-success p-3 rounded border border-secondary"><code>[
  {
    "id": 1,
    "name": "Port of Tokyo",
    "country_id": 1,
    "country_name": "Japan",
    "lat": "35.6190",
    "lng": "139.7781"
  }
]</code></pre>
        </div>

        <div class="mb-5" id="api-ships">
            <h3 class="text-light border-bottom border-secondary pb-2">Get Live Ships (Vessel Tracking)</h3>
            <p class="text-muted">Returns active cargo ships and their calculated polyline route geometry between Origin and Destination ports.</p>
            <div class="bg-dark p-3 rounded border border-secondary mb-3 d-flex align-items-center">
                <span class="badge bg-success fs-6 me-3">GET</span> 
                <code class="fs-5 text-light">/api/ships</code>
            </div>
            <h5 class="text-light mt-4">Example Response</h5>
            <pre class="bg-dark text-success p-3 rounded border border-secondary"><code>[
  {
    "id": 1,
    "name": "Ever Given",
    "type": "Container Ship",
    "route_geometry": "[[35.6190, 139.7781], [34.0, 138.0], [1.2721, 103.8016]]",
    "origin_port": { "name": "Port of Tokyo" },
    "destination_port": { "name": "Port of Singapore" }
  }
]</code></pre>
        </div>

        <div class="mb-5" id="api-compare">
            <h3 class="text-light border-bottom border-secondary pb-2">Compare Countries</h3>
            <p class="text-muted">Performs a 1-vs-1 comparison of economic indicators and risk scores between two specified countries.</p>
            <div class="bg-dark p-3 rounded border border-secondary mb-3 d-flex align-items-center">
                <span class="badge bg-warning text-dark fs-6 me-3">POST</span> 
                <code class="fs-5 text-light">/api/compare</code>
            </div>
            <h5 class="text-light">Request Body</h5>
            <table class="table table-dark table-bordered">
                <thead><tr><th>Parameter</th><th>Type</th><th>Description</th></tr></thead>
                <tbody>
                    <tr><td><code>country1_id</code></td><td>int</td><td>ID of the first country</td></tr>
                    <tr><td><code>country2_id</code></td><td>int</td><td>ID of the second country</td></tr>
                </tbody>
            </table>
        </div>

        <div class="mb-5" id="api-risk">
            <h3 class="text-light border-bottom border-secondary pb-2">Recalculate Risk Score</h3>
            <p class="text-muted">Triggers the Risk Scoring Engine to recalculate a specific country's risk based on weather, inflation, and news sentiment.</p>
            <div class="bg-dark p-3 rounded border border-secondary mb-3 d-flex align-items-center">
                <span class="badge bg-warning text-dark fs-6 me-3">POST</span> 
                <code class="fs-5 text-light">/risk/recalculate</code>
            </div>
            <h5 class="text-light">Request Body</h5>
            <table class="table table-dark table-bordered">
                <thead><tr><th>Parameter</th><th>Type</th><th>Description</th></tr></thead>
                <tbody>
                    <tr><td><code>country_id</code></td><td>int</td><td>ID of the target country</td></tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<style>
    .list-group-item:hover {
        background-color: rgba(255,255,255,0.05) !important;
    }
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection
