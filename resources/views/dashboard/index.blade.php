@extends('layouts.app')
@section('title', 'Global Map Dashboard')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 80vh;
        width: 100%;
        border-radius: 10px;
        border: 1px solid #333;
    }
    .leaflet-popup-content-wrapper, .leaflet-popup-tip {
        background-color: #1e1e1e;
        color: #e0e0e0;
        border: 1px solid #00ffcc;
    }
    .country-list {
        height: 80vh;
        overflow-y: auto;
    }
    /* Scrollbar styling for country list */
    .country-list::-webkit-scrollbar {
        width: 6px;
    }
    .country-list::-webkit-scrollbar-track {
        background: #121212; 
    }
    .country-list::-webkit-scrollbar-thumb {
        background: #333; 
        border-radius: 3px;
    }
    .country-item {
        background-color: #1e1e1e;
        border: 1px solid #333;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: 0.3s;
    }
    .country-item:hover {
        border-color: #00ffcc;
        background-color: #232323;
    }
</style>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-neon m-0"><i class="fas fa-map-marked-alt me-2"></i> Live Operations Map</h2>
        <div>
            <span class="badge bg-secondary me-2">Phase 2 Data (Countries)</span>
            <span class="badge bg-dark border border-secondary text-muted" title="Will be implemented in Phase 8">Ports & Ships (Phase 8)</span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Panel: List -->
    <div class="col-md-4 country-list pe-3">
        <h5 class="text-muted mb-3">Monitored Locations</h5>
        @foreach($countries as $country)
        <div class="country-item shadow-sm country-click" data-lat="{{ (float) $country->lat }}" data-lng="{{ (float) $country->lng }}" style="cursor: pointer;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="text-light m-0">{{ $country->name }}</h5>
                    <small class="text-neon"><i class="fas fa-city me-1"></i> {{ $country->capital }}</small>
                </div>
                <span class="badge bg-dark border border-secondary">{{ $country->code }}</span>
            </div>
            <div class="mt-3 text-muted d-flex justify-content-between" style="font-size: 0.85rem;">
                <span><i class="fas fa-users me-1"></i> {{ number_format($country->population) }}</span>
                <span><i class="fas fa-money-bill me-1"></i> {{ $country->currency_code }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Right Panel: Map -->
    <div class="col-md-8">
        <div id="map" class="shadow-lg"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map centering roughly globally
    var map = L.map('map').setView([20.0, 0.0], 2);

    // Add Dark Matter TileLayer (Cocok untuk Dark Mode)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Custom Neon Marker Icon
    var neonIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:#00ffcc;width:12px;height:12px;border-radius:50%;box-shadow:0 0 10px #00ffcc;'></div>",
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    // Store markers
    var markers = [];

    // Add Country Markers
    @foreach($countries as $country)
        (function() {
            var lat = {{ (float) $country->lat }};
            var lng = {{ (float) $country->lng }};
            var name = `{{ addslashes($country->name) }}`;
            var capital = `{{ addslashes($country->capital) }}`;
            var url = `{{ route('countries.show', $country->id) }}`;
            
            var marker = L.marker([lat, lng], {icon: neonIcon}).addTo(map);
            marker.bindPopup(
                "<div class='text-center'>" +
                "<b class='text-neon' style='font-size:1.1rem;'>" + name + "</b><br>" +
                "<span class='text-muted'>Capital: " + capital + "</span><br><br>" +
                "<a href='" + url + "' class='btn btn-sm btn-outline-info w-100 py-1' style='font-size:0.8rem;'>View Analysis</a>" +
                "</div>"
            );
            markers.push(marker);
        })();
    @endforeach

    // Add event listeners to list items
    document.querySelectorAll('.country-click').forEach(function(item) {
        item.addEventListener('click', function() {
            var lat = parseFloat(this.getAttribute('data-lat'));
            var lng = parseFloat(this.getAttribute('data-lng'));
            if(!isNaN(lat) && !isNaN(lng)) {
                map.flyTo([lat, lng], 5, {
                    animate: true,
                    duration: 1.5
                });
            }
        });
    });
</script>
@endsection
