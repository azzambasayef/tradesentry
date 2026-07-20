@extends('layouts.app')

@section('title', 'Risk Engine')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="text-light m-0 fw-bold"><i class="fas fa-chart-line text-warning me-2"></i>Supply Chain Risk Engine</h2>
                <p class="text-muted mt-1 mb-0">Algorithmic risk scoring based on weather, inflation, currency, and geopolitical news.</p>
            </div>
            <div>
                <form action="{{ route('risk.recalculate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning shadow-sm hover-effect">
                        <i class="fas fa-sync-alt me-2"></i>Recalculate Global Risk
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success bg-transparent border-success text-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Parameter Weights Widgets -->
    <div class="col-12">
        <div class="card border-secondary bg-transparent shadow-lg mb-2">
            <div class="card-header border-secondary bg-transparent">
                <h6 class="text-light m-0 text-uppercase fw-bold" style="letter-spacing: 1px;">Custom Algorithm Weights</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @foreach($weights as $weight)
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="p-3 border border-secondary rounded hover-effect" style="background-color: var(--card-bg);">
                            <div class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">
                                @if($weight->category == 'weather') <i class="fas fa-cloud-showers-heavy text-info me-1"></i>
                                @elseif($weight->category == 'inflation') <i class="fas fa-arrow-trend-up text-danger me-1"></i>
                                @elseif($weight->category == 'currency') <i class="fas fa-coins text-success me-1"></i>
                                @elseif($weight->category == 'news') <i class="fas fa-newspaper text-accent me-1"></i>
                                @endif
                                {{ ucfirst($weight->category) }} Risk
                            </div>
                            <h3 class="text-light m-0 fw-bold">{{ number_format((float)$weight->weight * 100, 0) }}%</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card bg-transparent border-danger text-center hover-effect shadow-lg">
                    <div class="card-body py-4">
                        <h1 class="text-danger fw-bold m-0">{{ $totalCritical }}</h1>
                        <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Critical Countries</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-transparent border-warning text-center hover-effect shadow-lg">
                    <div class="card-body py-4">
                        <h1 class="text-warning fw-bold m-0">{{ $totalHigh }}</h1>
                        <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">High Risk Countries</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-transparent border-secondary text-center hover-effect shadow-lg">
                    <div class="card-body py-4">
                        <h1 class="text-light fw-bold m-0">{{ $totalMedium }}</h1>
                        <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Medium Risk</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-transparent border-success text-center hover-effect shadow-lg">
                    <div class="card-body py-4">
                        <h1 class="text-success fw-bold m-0">{{ number_format($avgScore, 1) }}</h1>
                        <span class="text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Global Avg Score</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Highest Risk Countries Table -->
    <div class="col-12 mt-4">
        <div class="card border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="text-light m-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Global Risk Database (Top 50 Displayed)</h5>
                <div style="width: 250px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchRisk" class="form-control bg-dark border-secondary text-light" placeholder="Search any country...">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px;">
                    <table class="table table-dark table-hover table-striped mb-0 text-center align-middle" id="riskTable">
                        <thead class="sticky-top" style="z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Country</th>
                                <th>Weather</th>
                                <th>Inflation</th>
                                <th>Currency</th>
                                <th>News/Geo</th>
                                <th>Total Score</th>
                                <th>Risk Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($risks as $index => $risk)
                                <tr class="risk-row {{ $index >= 50 ? 'd-none' : '' }}" data-country="{{ strtolower($risk->country->name . ' ' . $risk->country->code_alpha3) }}">
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-start">
                                        {{ $risk->country->name }} <span class="badge bg-secondary ms-1">{{ $risk->country->code_alpha3 }}</span>
                                    </td>
                                    <td class="{{ $risk->weather_risk > 75 ? 'text-danger' : 'text-muted' }}">{{ number_format($risk->weather_risk, 1) }}</td>
                                    <td class="{{ $risk->inflation_risk > 75 ? 'text-danger' : 'text-muted' }}">{{ number_format($risk->inflation_risk, 1) }}</td>
                                    <td class="{{ $risk->currency_risk > 75 ? 'text-danger' : 'text-muted' }}">{{ number_format($risk->currency_risk, 1) }}</td>
                                    <td class="{{ $risk->news_risk > 75 ? 'text-danger' : 'text-muted' }}">{{ number_format($risk->news_risk, 1) }}</td>
                                    <td>
                                        <div class="progress" style="height: 10px; background-color: #1e293b;">
                                            <div class="progress-bar 
                                                @if($risk->total_score >= 75) bg-danger
                                                @elseif($risk->total_score >= 50) bg-warning
                                                @elseif($risk->total_score >= 25) bg-info
                                                @else bg-success @endif" 
                                                role="progressbar" style="width: {{ $risk->total_score }}%;"></div>
                                        </div>
                                        <small class="fw-bold">{{ number_format($risk->total_score, 1) }} / 100</small>
                                    </td>
                                    <td>
                                        @if($risk->risk_level == 'critical')
                                            <span class="badge bg-danger text-uppercase p-2"><i class="fas fa-skull-crossbones me-1"></i> Critical</span>
                                        @elseif($risk->risk_level == 'high')
                                            <span class="badge bg-warning text-dark text-uppercase p-2"><i class="fas fa-exclamation me-1"></i> High</span>
                                        @elseif($risk->risk_level == 'medium')
                                            <span class="badge bg-info text-dark text-uppercase p-2"><i class="fas fa-minus me-1"></i> Medium</span>
                                        @else
                                            <span class="badge bg-success text-uppercase p-2"><i class="fas fa-check me-1"></i> Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('countries.show', $risk->country->id) }}" class="btn btn-sm btn-outline-primary-blue">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchRisk');
        const rows = document.querySelectorAll('.risk-row');
        
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            
            rows.forEach((row, index) => {
                if (query === '') {
                    // Reset to show only top 50 when search is empty
                    if (index < 50) {
                        row.classList.remove('d-none');
                    } else {
                        row.classList.add('d-none');
                    }
                } else {
                    // Search all 250 rows
                    const countryData = row.getAttribute('data-country');
                    if (countryData.includes(query)) {
                        row.classList.remove('d-none');
                    } else {
                        row.classList.add('d-none');
                    }
                }
            });
        });
    });
</script>

<style>
    .hover-effect:hover {
        border-color: var(--primary-blue) !important;
        box-shadow: 0 0 15px rgba(10, 132, 255, 0.15) !important;
        transform: translateY(-2px);
        transition: all 0.3s;
    }
    .btn-outline-primary-blue {
        color: var(--primary-blue);
        border-color: var(--primary-blue);
    }
    .btn-outline-primary-blue:hover {
        background-color: var(--primary-blue);
        color: white;
    }
</style>
@endsection
