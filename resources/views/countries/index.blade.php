@extends('layouts.app')
@section('title', 'Countries Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-2">
    <h2 class="text-neon m-0"><i class="fas fa-globe me-2"></i> Monitored Countries</h2>
</div>

<div class="row">
    @foreach($countries as $country)
    <div class="col-md-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-body text-center d-flex flex-column">
                <img src="https://flagcdn.com/w80/{{ strtolower($country->code) }}.png" alt="Flag of {{ $country->name }}" class="mx-auto mb-3 shadow-sm rounded border border-secondary" style="height: 40px; object-fit: cover; width: 60px;">
                <h4 class="card-title text-light">{{ $country->name }}</h4>
                <p class="text-muted mb-2">{{ $country->region }} ({{ $country->code }})</p>
                <div class="d-flex justify-content-around mb-4 mt-3">
                    <div>
                        <small class="text-muted d-block">Capital</small>
                        <span class="fw-bold">{{ $country->capital ?? '-' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Currency</small>
                        <span class="fw-bold">{{ $country->currency_code ?? '-' }}</span>
                    </div>
                </div>
                <div class="mt-auto">
                    <a href="{{ route('countries.show', $country->id) }}" class="btn btn-neon w-100">View Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
