@extends('layouts.app')

@section('title', 'Live Operations')

@section('content')
<div class="row">
    <!-- Left Panel: List -->
    <div class="col-md-4 country-list pe-3" style="max-height: 80vh; overflow-y: auto;">
        <h5 class="text-muted mb-3"><i class="fas fa-list text-primary-blue me-2"></i>Monitored Locations</h5>
        
        <div id="country-container">
            <div class="text-center text-muted py-5">
                <i class="fas fa-spinner fa-spin fs-2 mb-3 text-primary-blue"></i>
                <p>Loading global data...</p>
            </div>
        </div>
    </div>

    <!-- Right Panel: Map -->
    <div class="col-md-8 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-light m-0"><i class="fas fa-map-marked-alt text-primary-blue me-2"></i>Live Operations Map</h4>
            <div>
                <span class="badge bg-primary-blue me-2">Phase 2 Data (Countries)</span>
                <span class="badge bg-secondary">Ports & Ships (Phase 8)</span>
            </div>
        </div>
        
        <div class="position-relative">
            <div id="map" class="shadow-lg border border-secondary rounded" style="height: 70vh;"></div>
            
            <!-- Floating Live Ticker (News & Currency) -->
            <div class="position-absolute bottom-0 start-0 m-3 p-2 rounded shadow-lg" 
                 style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); border: 1px solid var(--primary-blue); z-index: 1000; width: calc(100% - 2rem); display: flex; align-items: center;">
                <span class="badge bg-primary-blue me-3 px-2 py-1 text-uppercase" style="letter-spacing: 1px;">Live</span>
                <marquee behavior="scroll" direction="left" class="text-light m-0" style="font-size: 0.95rem; font-weight: 500;">
                    <i class="fas fa-newspaper text-accent me-1"></i> <span id="news-ticker-1">GNews API Pending (Phase 7): Waiting for real-time geopolitical updates...</span> 
                    <span class="mx-3 text-muted">|</span> 
                    <i class="fas fa-exchange-alt text-success me-1"></i> <span id="currency-ticker-1">ExchangeRate API Pending (Phase 5): USD/IDR rates will appear here...</span> 
                    <span class="mx-3 text-muted">|</span> 
                    <i class="fas fa-newspaper text-accent me-1"></i> <span id="news-ticker-2">Fetching global supply chain risks...</span>
                </marquee>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Dark Theme Map overrides */
    .leaflet-popup-content-wrapper {
        background-color: var(--card-bg);
        color: var(--text-light);
        border: 1px solid var(--primary-blue);
        border-radius: 8px;
    }
    .leaflet-popup-tip {
        background-color: var(--card-bg);
        border: 1px solid var(--primary-blue);
    }
    /* Glow effect for country item */
    .country-item {
        background-color: var(--card-bg);
        border: 1px solid #1e293b;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }
    .country-item:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 0 15px rgba(10, 132, 255, 0.2);
        transform: translateY(-2px);
    }
</style>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize Map with Dark Matter tiles
    const map = L.map('map', {
        worldCopyJump: true,
        minZoom: 2
    }).setView([20.0, 0.0], 2);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Primary Blue Marker Icon
    const blueIcon = L.divIcon({
        className: 'custom-div-icon',
        html: "<div style='background-color:var(--primary-blue);width:12px;height:12px;border-radius:50%;box-shadow:0 0 15px var(--primary-blue); border: 2px solid white;'></div>",
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    let allCountries = [];
    let markers = [];

    // Fetch Countries via AJAX (ES6 Fetch)
    const fetchCountries = async () => {
        try {
            const response = await fetch('/api/countries');
            const data = await response.json();
            allCountries = data;
            renderCountries(data);
        } catch (error) {
            console.error('Error fetching countries:', error);
            document.getElementById('country-container').innerHTML = '<div class="alert alert-danger">Failed to load data</div>';
        }
    };

    const renderCountries = (countries) => {
        const container = document.getElementById('country-container');
        container.innerHTML = '';
        
        // Clear old markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        if(countries.length === 0) {
            container.innerHTML = '<div class="text-muted text-center py-4">No locations found.</div>';
            return;
        }

        countries.forEach(country => {
            // Plot marker
            const lat = parseFloat(country.lat);
            const lng = parseFloat(country.lng);
            
            if(!isNaN(lat) && !isNaN(lng)) {
                const marker = L.marker([lat, lng], {icon: blueIcon}).addTo(map);
                const url = `/countries/${country.id}`;
                marker.bindPopup(`
                    <div class='text-center p-2'>
                        <b class='text-primary-blue' style='font-size:1.1rem;'>${country.name}</b><br>
                        <span class='text-muted'>Capital: ${country.capital || 'N/A'}</span><br><br>
                        <a href='${url}' class='btn btn-sm btn-primary-blue w-100 py-1' style='font-size:0.8rem; border-radius:6px;'>View Analysis</a>
                    </div>
                `);
                markers.push(marker);
            }

            // Create list item
            const item = document.createElement('div');
            item.className = 'country-item country-click';
            item.style.cursor = 'pointer';
            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-light m-0 fw-bold">${country.name}</h5>
                        <small class="text-accent"><i class="fas fa-city me-1"></i> ${country.capital || 'N/A'}</small>
                    </div>
                    <span class="badge bg-dark border border-secondary">${country.code}</span>
                </div>
                <div class="mt-3 text-muted d-flex justify-content-between" style="font-size: 0.85rem;">
                    <span><i class="fas fa-users me-1"></i> ${new Intl.NumberFormat().format(country.population)}</span>
                    <span><i class="fas fa-money-bill me-1"></i> ${country.currency_code || '-'}</span>
                </div>
            `;
            
            item.addEventListener('click', () => {
                if(!isNaN(lat) && !isNaN(lng)) {
                    map.flyTo([lat, lng], 5, { animate: true, duration: 1.5 });
                }
            });

            container.appendChild(item);
        });
    };

    // Global Search Listener
    document.addEventListener('DOMContentLoaded', () => {
        fetchCountries();

        const searchInput = document.querySelector('.global-search');
        if(searchInput) {
            searchInput.addEventListener('input', (e) => {
                const keyword = e.target.value.toLowerCase();
                const filtered = allCountries.filter(c => 
                    c.name.toLowerCase().includes(keyword) || 
                    (c.capital && c.capital.toLowerCase().includes(keyword))
                );
                renderCountries(filtered);
            });
        }
    });
</script>
@endsection
