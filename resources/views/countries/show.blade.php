@extends('layouts.app')
@section('title', $country->name . ' Dashboard')

@section('content')
<div class="mb-4 d-flex align-items-center">
    <a href="{{ route('countries.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i> Back</a>
    <h2 class="text-neon m-0">{{ $country->name }} Dashboard</h2>
</div>

<div class="row">
    <!-- Basic Info -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header text-neon"><i class="fas fa-info-circle me-1"></i> General Information</div>
            <div class="card-body">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between">
                        <span class="text-muted">Capital</span> <strong>{{ $country->capital }}</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between">
                        <span class="text-muted">Region</span> <strong>{{ $country->region }}</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between">
                        <span class="text-muted">Population</span> <strong>{{ number_format($country->population) }}</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between">
                        <span class="text-muted">Currency</span> <strong>{{ $country->currency_name }} ({{ $country->currency_code }})</strong>
                    </li>
                    <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between">
                        <span class="text-muted">Area</span> <strong>{{ number_format($country->area) }} km²</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Weather Placeholder -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 opacity-75">
            <div class="card-header text-warning"><i class="fas fa-cloud-sun me-1"></i> Current Weather</div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-cloud text-muted mb-2" style="font-size: 3rem;"></i>
                <p class="text-muted text-center">Weather API Integration (Open-Meteo) will be added in Phase 4.</p>
                <div class="spinner-border text-secondary spinner-border-sm mt-2" role="status"></div>
            </div>
        </div>
    </div>

    <!-- Risk Placeholder -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 opacity-75">
            <div class="card-header text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Risk Score</div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h1 class="display-4 text-muted fw-bold">-</h1>
                <p class="text-muted text-center">Risk Scoring Engine will be activated in Phase 6.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Economic Data Placeholder -->
    <div class="col-12 mb-4">
        <div class="card opacity-75">
            <div class="card-header text-success"><i class="fas fa-chart-line me-1"></i> Economic Indicators (World Bank)</div>
            <div class="card-body text-center py-5">
                <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">GDP and Inflation data will be visualized here using Chart.js in Phase 3.</p>
            </div>
        </div>
    </div>
</div>
@endsection
