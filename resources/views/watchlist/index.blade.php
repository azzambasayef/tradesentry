@extends('layouts.app')
@section('title', 'My Watchlist')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-2">
    <h2 class="text-warning m-0"><i class="fas fa-star me-2"></i> My Watchlist</h2>
    <a href="{{ route('countries.index') }}" class="btn btn-outline-primary"><i class="fas fa-plus me-2"></i>Add More Countries</a>
</div>

@if($countries->isEmpty())
    <div class="text-center py-5">
        <i class="far fa-star fa-4x text-secondary mb-3"></i>
        <h4 class="text-muted">Your watchlist is empty</h4>
        <p class="text-muted">Browse the countries dashboard and click the star icon to add them here.</p>
        <a href="{{ route('countries.index') }}" class="btn btn-primary mt-3">Browse Countries</a>
    </div>
@else
    <div class="row">
        @foreach($countries as $country)
        <div class="col-md-4 mb-4 country-card" id="country-card-{{ $country->id }}">
            <div class="card shadow h-100 position-relative">
                <button onclick="toggleWatchlist({{ $country->id }}, true)" class="btn btn-link position-absolute top-0 end-0 m-2 text-warning" style="z-index: 10;">
                    <i class="fas fa-star fs-4" id="star-{{ $country->id }}"></i>
                </button>
                <div class="card-body text-center d-flex flex-column pt-4">
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
@endif

<script>
    function toggleWatchlist(countryId, removeCard = false) {
        fetch(`/watchlist/toggle/${countryId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (removeCard && data.status === 'removed') {
                const card = document.getElementById(`country-card-${countryId}`);
                if(card) {
                    card.style.transition = 'opacity 0.3s';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                }
            } else {
                const star = document.getElementById(`star-${countryId}`);
                if (data.status === 'added') {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-warning');
                    star.classList.remove('text-secondary');
                } else {
                    star.classList.remove('fas', 'text-warning');
                    star.classList.add('far', 'text-secondary');
                }
            }
        })
        .catch(error => console.error('Error toggling watchlist:', error));
    }
</script>
@endsection
