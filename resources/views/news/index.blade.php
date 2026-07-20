@extends('layouts.app')

@section('title', 'News Intelligence')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <div>
                <h2 class="text-light m-0 fw-bold"><i class="fas fa-newspaper text-accent me-2"></i>Global News Intelligence</h2>
                <p class="text-muted mt-1 mb-0">Real-time geopolitical and supply chain news analyzed by Lexicon AI.</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('news.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control bg-dark border-secondary text-light" placeholder="Search news..." value="{{ $search ?? '' }}">
                    </div>
                    <select name="country_id" class="form-select form-select-sm bg-dark text-light border-secondary shadow-sm" style="width: 200px;">
                        <option value="">-- All Countries --</option>
                        @foreach($countriesWithNews as $country)
                            <option value="{{ $country->id }}" {{ $countryId == $country->id ? 'selected' : '' }}>
                                {{ $country->name }} ({{ $country->code_alpha3 }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-info">Filter</button>
                </form>
                <form action="{{ route('news.fetch') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary-blue shadow-sm hover-effect">
                        <i class="fas fa-sync-alt me-2"></i>Fetch Latest News
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

    <!-- News Grid -->
    <div class="col-12">
        @if($news->count() > 0)
            <div class="row g-4">
                @foreach($news as $article)
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="card border-secondary shadow-lg hover-effect w-100 d-flex flex-column" style="background: rgba(15, 23, 42, 0.6);">
                        <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center pb-2">
                            <span class="badge bg-secondary text-light">
                                <i class="fas fa-globe me-1"></i> {{ $article->country->name ?? 'Global' }}
                            </span>
                            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($article->published_at)->diffForHumans() }}</small>
                        </div>
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title text-light fw-bold" style="font-size: 1.1rem; line-height: 1.4;">
                                {{ $article->title }}
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($article->description, 120) }}
                            </p>
                            
                            @if($article->sentimentData)
                            <div class="mt-3 p-3 rounded border border-secondary" style="background: rgba(0,0,0,0.2);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold text-uppercase">AI Sentiment Analysis</span>
                                    @if($article->sentimentData->sentiment == 'negative')
                                        <span class="badge bg-danger">NEGATIVE</span>
                                    @elseif($article->sentimentData->sentiment == 'positive')
                                        <span class="badge bg-success">POSITIVE</span>
                                    @else
                                        <span class="badge bg-secondary">NEUTRAL</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span><i class="fas fa-plus-circle text-success me-1"></i> Pos Words: {{ $article->sentimentData->positive_count }}</span>
                                    <span><i class="fas fa-minus-circle text-danger me-1"></i> Neg Words: {{ $article->sentimentData->negative_count }}</span>
                                    <span>Risk Score: <strong class="{{ $article->sentimentData->score >= 75 ? 'text-danger' : ($article->sentimentData->score >= 50 ? 'text-warning' : 'text-success') }}">{{ number_format($article->sentimentData->score, 1) }}</strong></span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-secondary d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="fas fa-satellite-dish me-1"></i> {{ $article->source_name }}</span>
                            <a href="{{ $article->source_url }}" target="_blank" class="btn btn-sm btn-outline-accent">
                                Read Full <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $news->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper text-muted mb-3" style="font-size: 4rem; opacity: 0.2;"></i>
                <h4 class="text-muted">No news articles found.</h4>
                <p class="text-muted">Click the 'Fetch Latest News' button to gather data or try a different filter.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4) !important;
        border-color: var(--primary-blue) !important;
    }
    .btn-primary-blue {
        background-color: var(--primary-blue);
        color: white;
        border: none;
    }
    .btn-primary-blue:hover {
        background-color: #0070e0;
        color: white;
    }
    .btn-outline-accent {
        color: var(--accent-blue);
        border-color: var(--accent-blue);
    }
    .btn-outline-accent:hover {
        background-color: var(--accent-blue);
        color: var(--dark-navy);
    }
</style>
@endsection
