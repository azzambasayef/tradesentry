@extends('layouts.app')

@section('title', 'Live Operations')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<!-- MarkerCluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<style>
    .leaflet-popup-content-wrapper { background-color: var(--card-bg); color: var(--text-light); border: 1px solid var(--primary-blue); border-radius: 8px; }
    .leaflet-popup-tip { background-color: var(--card-bg); border: 1px solid var(--primary-blue); }
    .country-item { background-color: var(--card-bg); border: 1px solid #1e293b; border-radius: 10px; padding: 15px; margin-bottom: 12px; transition: all 0.3s ease; }
    .country-item:hover { border-color: var(--primary-blue); box-shadow: 0 0 15px rgba(10, 132, 255, 0.2); transform: translateY(-2px); }
    .port-icon { color: var(--primary-blue); font-size: 14px; text-shadow: 0 2px 5px rgba(0,0,0,0.5); }
    .ship-icon { color: #ff3b30; font-size: 18px; text-shadow: 0 2px 5px rgba(255, 59, 48, 0.5); z-index: 1000 !important; }
</style>

<div class="row">
    <div class="col-md-3 country-list pe-3" style="max-height: 80vh; overflow-y: auto;">
        <h5 class="text-muted mb-3"><i class="fas fa-list text-primary-blue me-2"></i>Monitored Locations</h5>
        <div id="country-container"><div class="text-center py-5"><i class="fas fa-spinner fa-spin fs-2 text-primary-blue"></i></div></div>
    </div>

    <div class="col-md-9 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-light m-0"><i class="fas fa-map-marked-alt text-primary-blue me-2"></i>Live Operations Map</h4>
            <div class="d-flex align-items-center">
                <div class="form-check form-switch me-3"><input class="form-check-input" type="checkbox" id="toggleCountries" checked><label class="form-check-label text-light small" for="toggleCountries"> Countries</label></div>
                <div class="form-check form-switch me-3"><input class="form-check-input" type="checkbox" id="togglePorts" checked><label class="form-check-label text-light small" for="togglePorts"> Ports</label></div>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="toggleShips" checked><label class="form-check-label text-light small" for="toggleShips"> Ships</label></div>
            </div>
        </div>
        
        <div class="position-relative">
            <div id="map" class="shadow-lg border border-secondary rounded" style="height: 70vh; background: #000;"></div>
            
            <div class="position-absolute bottom-0 start-0 m-3 p-2 rounded shadow-lg" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); border: 1px solid var(--primary-blue); z-index: 1000; width: calc(100% - 2rem); display: flex; align-items: center;">
                <span class="badge bg-primary-blue me-3 px-2 py-1 text-uppercase">Live</span>
                <marquee behavior="scroll" direction="left" class="text-light m-0" style="font-size: 0.95rem; font-weight: 500;">
                    @if(isset($latestNews) && $latestNews->count() > 0)
                        @foreach($latestNews as $news)
                            <i class="fas fa-newspaper text-accent me-1"></i> <span class="fw-bold text-info">[{{ strtoupper($news->country->name) }}]</span> <a href="{{ $news->source_url }}" target="_blank" class="text-light text-decoration-none hover-effect">{{ $news->title }}</a> <span class="mx-3 text-muted">|</span> 
                        @endforeach
                    @else
                        <i class="fas fa-newspaper text-accent me-1"></i> Waiting for real-time geopolitical updates... <span class="mx-3 text-muted">|</span> 
                    @endif
                </marquee>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    // --- Maritime Waypoint Routing (MWR) Engine ---
    const waypoints = {
        'Malacca': { lat: 2.8, lng: 101.3 },
        'Suez': { lat: 31.25, lng: 32.3 },
        'Gibraltar': { lat: 35.9, lng: -5.5 },
        'Panama': { lat: 9.1, lng: -79.7 },
        'PacificMid': { lat: 20.0, lng: -160.0 },
        'AtlanticMid': { lat: 30.0, lng: -40.0 },
        'IndianMid': { lat: -10.0, lng: 70.0 }
    };

    function calculateDistanceNM(lat1, lon1, lat2, lon2) {
        const R = 3440.065; 
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2); 
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function getPositionOnRoute(geometry, progress) {
        if (!geometry || geometry.length < 2) return null;
        let totalDist = 0;
        let segments = [];
        for(let i=0; i<geometry.length-1; i++) {
            let d = calculateDistanceNM(geometry[i][1], geometry[i][0], geometry[i+1][1], geometry[i+1][0]);
            segments.push(d);
            totalDist += d;
        }
        
        let targetDist = totalDist * progress;
        let currentDist = 0;
        
        for(let i=0; i<segments.length; i++) {
            if(targetDist <= currentDist + segments[i]) {
                let segmentProgress = segments[i] === 0 ? 0 : (targetDist - currentDist) / segments[i];
                let p1 = geometry[i];
                let p2 = geometry[i+1];
                let lat = p1[1] + (p2[1] - p1[1]) * segmentProgress;
                
                let lngDiff = p2[0] - p1[0];
                if (lngDiff > 180) lngDiff -= 360;
                if (lngDiff < -180) lngDiff += 360;
                let lng = p1[0] + lngDiff * segmentProgress;
                
                return { lat: lat, lng: lng, remainingDist: totalDist - targetDist, totalDist: totalDist };
            }
            currentDist += segments[i];
        }
        return { lat: geometry[geometry.length-1][1], lng: geometry[geometry.length-1][0], remainingDist: 0, totalDist: totalDist };
    }

    function formatETA(hours) {
        if (hours < 24) return Math.round(hours) + ' hours';
        return `${Math.floor(hours / 24)}d ${Math.round(hours % 24)}h`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const map = L.map('map', { center: [20.0, 0.0], zoom: 2, minZoom: 2, maxBounds: [[-90, -180], [90, 180]], worldCopyJump: true });
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; CARTO' }).addTo(map);

        const countryIcon = L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:var(--primary-blue);width:16px;height:16px;border-radius:50%;border: 2px solid white;'></div>", iconSize: [16, 16] });
        const portIcon = L.divIcon({ html: '<i class="fas fa-anchor port-icon"></i>', className: 'custom-div-icon', iconSize: [14, 14] });
        const shipIcon = L.divIcon({ html: '<i class="fas fa-ship ship-icon"></i>', className: 'custom-div-icon', iconSize: [18, 18] });

        const countryLayer = L.layerGroup().addTo(map);
        let portCluster = L.markerClusterGroup({ chunkedLoading: true, maxClusterRadius: 50 }).addTo(map);
        let shipCluster = L.markerClusterGroup({ chunkedLoading: true, maxClusterRadius: 40, iconCreateFunction: (c) => L.divIcon({ html: `<div style="background:rgba(255,59,48,0.8);color:white;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;border:2px solid white;">${c.getChildCount()}</div>`, className: 'ship-cluster' }) }).addTo(map);

        let activeRouteLine = null;

        Promise.all([fetch('/api/countries').then(r=>r.json()), fetch('/api/ports').then(r=>r.json()), fetch('/api/ships').then(r=>r.json())]).then(([countries, ports, ships]) => {
            renderCountries(countries);
            renderPorts(ports);
            renderShips(ships);
        });

        function renderCountries(countries) {
            let container = document.getElementById('country-container');
            container.innerHTML = ''; // Clear loading
            
            countries.forEach(c => {
                let marker = null;
                if(c.lat && c.lng) {
                    marker = L.marker([c.lat, c.lng], {icon: countryIcon});
                    marker.bindPopup(`<b>${c.name}</b><hr class="my-1"><small>Capital: ${c.capital || 'N/A'}<br>Pop: ${Number(c.population).toLocaleString()}</small>`);
                    countryLayer.addLayer(marker);
                }
                
                let item = document.createElement('div');
                item.className = 'country-item';
                item.style.cursor = 'pointer';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="m-0 text-light fw-bold">${c.name}</h6>
                        <span class="badge bg-secondary" style="font-size: 0.65rem;">${c.code_alpha3}</span>
                    </div>
                    <div class="small text-muted mb-1" style="font-size: 0.75rem;">
                        <i class="fas fa-city text-primary-blue me-1"></i> ${c.capital || 'N/A'}
                    </div>
                    <div class="d-flex justify-content-between small text-muted" style="font-size: 0.75rem;">
                        <span><i class="fas fa-users text-warning me-1"></i> ${c.population ? Number(c.population).toLocaleString() : 'N/A'}</span>
                        <span><i class="fas fa-coins text-success me-1"></i> ${c.currency_code || 'N/A'}</span>
                    </div>
                `;
                
                item.addEventListener('click', () => {
                    if (c.lat && c.lng) {
                        map.flyTo([c.lat, c.lng], 5, { duration: 1.5 });
                        if (marker) marker.openPopup();
                    }
                });
                
                container.appendChild(item);
            });
        }

        function renderPorts(ports) {
            ports.forEach(port => {
                if (port.lat && port.lng) {
                    portCluster.addLayer(L.marker([port.lat, port.lng], {icon: portIcon}).bindPopup(`<b><i class="fas fa-anchor"></i> ${port.name}</b><br><small>${port.country_name}</small>`));
                }
            });
        }

        function renderShips(ships) {
            ships.forEach(ship => {
                if(ship.origin_port && ship.destination_port && ship.route_geometry) {
                    let geometry = null;
                    try { geometry = JSON.parse(ship.route_geometry); } catch(e) {}
                    if(!geometry || geometry.length < 2) return;

                    const posInfo = getPositionOnRoute(geometry, ship.progress_percentage / 100);
                    if(!posInfo) return;
                    
                    const etaHours = posInfo.remainingDist / parseFloat(ship.speed_knots);
                    
                    const marker = L.marker([posInfo.lat, posInfo.lng], {icon: shipIcon, zIndexOffset: 1000});
                    marker.bindPopup(`
                        <div style="min-width: 200px;">
                            <b class="text-danger"><i class="fas fa-ship"></i> ${ship.name}</b><br>
                            <small>${ship.origin_port.name} ➔ ${ship.destination_port.name}</small><br>
                            <hr class="my-1 border-secondary">
                            <small class="text-muted">ETA: <b class="text-accent">${formatETA(etaHours)}</b> (${Math.round(posInfo.remainingDist)} NM)</small>
                        </div>
                    `);
                    
                    marker.on('click', () => {
                        if(activeRouteLine) map.removeLayer(activeRouteLine);
                        activeRouteLine = L.polyline(geometry.map(p => [p[1], p[0]]), { color: '#ff3b30', weight: 2, dashArray: '5, 10' }).addTo(map);
                    });
                    shipCluster.addLayer(marker);
                }
            });
        }

        map.on('click', () => { if(activeRouteLine) map.removeLayer(activeRouteLine); });
        document.getElementById('toggleCountries').addEventListener('change', e => e.target.checked ? map.addLayer(countryLayer) : map.removeLayer(countryLayer));
        document.getElementById('togglePorts').addEventListener('change', e => e.target.checked ? map.addLayer(portCluster) : map.removeLayer(portCluster));
        document.getElementById('toggleShips').addEventListener('change', e => { e.target.checked ? map.addLayer(shipCluster) : (map.removeLayer(shipCluster), activeRouteLine && map.removeLayer(activeRouteLine)); });
    });
</script>
@endsection
