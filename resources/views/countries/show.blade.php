@extends('layouts.app')

@section('title', $country->name . ' - Risk Intelligence')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <h2 class="text-light m-0 fw-bold me-3">{{ $country->name }}</h2>
                <span class="badge bg-secondary fs-6 border border-dark">{{ $country->code }} / {{ $country->code_alpha3 }}</span>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-2"></i>Back to Map</a>
        </div>
        <p class="text-muted"><i class="fas fa-map-marker-alt text-primary-blue me-2"></i>Region: {{ $country->region }} ({{ $country->subregion }}) | Capital: {{ $country->capital ?? 'N/A' }}</p>
    </div>

    <!-- General Info Widget -->
    <div class="col-md-4">
        <div class="card h-100 border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent text-light">
                <h5 class="m-0"><i class="fas fa-info-circle text-primary-blue me-2"></i>General Information</h5>
            </div>
            <div class="card-body text-muted">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent text-muted border-secondary d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2"></i>Population</span>
                        <strong class="text-light">{{ number_format($country->population) }}</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-muted border-secondary d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-ruler-combined me-2"></i>Area</span>
                        <strong class="text-light">{{ number_format($country->area) }} km²</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-muted border-secondary d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-coins me-2"></i>Currency</span>
                        <strong class="text-light">{{ $country->currency_name }} ({{ $country->currency_code }})</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Phase 3: Economic Indicators Widget (World Bank API) -->
    <div class="col-md-4">
        <div class="card h-100 border-secondary shadow-lg position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 m-2"><span class="badge bg-success">World Bank API</span></div>
            <div class="card-header border-secondary bg-transparent text-light">
                <h5 class="m-0"><i class="fas fa-chart-bar text-accent-blue me-2"></i>Economic Intelligence</h5>
            </div>
            <div class="card-body">
                @if($gdpData)
                    <div class="mb-4">
                        <small class="text-muted d-block text-uppercase" style="letter-spacing: 1px;">Gross Domestic Product ({{ $gdpData->year }})</small>
                        <h3 class="text-light fw-bold m-0">${{ number_format($gdpData->value) }}</h3>
                    </div>
                @else
                    <div class="mb-4">
                        <small class="text-muted d-block text-uppercase" style="letter-spacing: 1px;">Gross Domestic Product</small>
                        <h4 class="text-warning m-0">Data Unavailable</h4>
                    </div>
                @endif

                @if($inflationData)
                    <div>
                        <small class="text-muted d-block text-uppercase" style="letter-spacing: 1px;">Inflation Rate ({{ $inflationData->year }})</small>
                        <h3 class="{{ $inflationData->value > 5 ? 'text-danger' : 'text-success' }} fw-bold m-0">
                            {{ number_format($inflationData->value, 2) }}%
                            <i class="fas {{ $inflationData->value > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} ms-1 fs-5"></i>
                        </h3>
                    </div>
                @else
                    <div>
                        <small class="text-muted d-block text-uppercase" style="letter-spacing: 1px;">Inflation Rate</small>
                        <h4 class="text-warning m-0">Data Unavailable</h4>
                    </div>
                @endif
            </div>
            <div class="card-footer border-secondary bg-transparent text-muted" style="font-size: 0.8rem;">
                <i class="fas fa-database me-1"></i> Cached locally in database for speed.
            </div>
        </div>
    </div>

    <!-- Phase 4: Live Weather Widget (Open-Meteo API) -->
    <div class="col-md-4">
        <div class="card h-100 border-secondary shadow-lg position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 m-2"><span class="badge bg-primary-blue">Live API</span></div>
            <div class="card-header border-secondary bg-transparent text-light">
                <h5 class="m-0"><i class="fas fa-cloud-sun-rain text-primary-blue me-2"></i>Live Weather Data</h5>
            </div>
            <div class="card-body">
                @if($weather)
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-thermometer-half text-danger fa-3x me-3"></i>
                        <div>
                            <small class="text-muted d-block text-uppercase" style="letter-spacing: 1px;">Current Temp</small>
                            <h2 class="text-light fw-bold m-0">{{ $weather['temperature_2m'] ?? '--' }}°C</h2>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-wind text-accent-blue fa-2x me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Wind Speed</small>
                                    <h5 class="text-light m-0">{{ $weather['wind_speed_10m'] ?? '--' }} <span style="font-size: 0.7rem;">km/h</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tint text-primary-blue fa-2x me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Humidity</small>
                                    <h5 class="text-light m-0">{{ $weather['relative_humidity_2m'] ?? '--' }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-satellite-dish text-muted fa-3x mb-3"></i>
                        <p class="text-muted">Unable to connect to weather satellite.</p>
                    </div>
                @endif
            </div>
            <div class="card-footer border-secondary bg-transparent text-muted" style="font-size: 0.8rem;">
                <i class="fas fa-sync-alt fa-spin me-1"></i> Real-time fetch via Open-Meteo.
            </div>
        </div>
    </div>
    
    <!-- Mini Map -->
    <div class="col-12 mt-4">
        <div class="card border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent text-light">
                <h5 class="m-0"><i class="fas fa-crosshairs text-primary-blue me-2"></i>Geographical Position</h5>
            </div>
            <div class="card-body p-0">
                <div id="mini-map" style="height: 300px; width: 100%; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;"></div>
            </div>
        </div>
    </div>

</div>

<!-- Leaflet Setup for Mini Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .text-accent-blue { color: var(--accent-blue) !important; }
</style>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $country->lat ?? 0 }};
        const lng = {{ $country->lng ?? 0 }};
        
        const miniMap = L.map('mini-map', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false
        }).setView([lat, lng], 4);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            subdomains: 'abcd',
        }).addTo(miniMap);
        
        const blueIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:var(--primary-blue);width:16px;height:16px;border-radius:50%;box-shadow:0 0 20px var(--primary-blue); border: 3px solid white;'></div>",
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        
        L.marker([lat, lng], {icon: blueIcon}).addTo(miniMap);
    });
</script>
@endsection
