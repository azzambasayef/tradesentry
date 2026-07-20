@extends('layouts.app')

@section('title', 'Currency Dashboard')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="text-light m-0 fw-bold"><i class="fas fa-money-bill-wave text-success me-2"></i>Global Currency Intelligence</h2>
                <p class="text-muted mt-1 mb-0">Real-time exchange rates and historical trend analysis against USD base.</p>
            </div>
            <div>
                <form action="{{ route('currencies.index') }}" method="GET" class="d-flex gap-2">
                    <select name="currency" class="form-select bg-dark text-light border-secondary" onchange="this.form.submit()">
                        @foreach($rates as $rate)
                            <option value="{{ $rate->target_currency }}" {{ $selectedCurrency == $rate->target_currency ? 'selected' : '' }}>
                                {{ $rate->target_currency }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Chart Widget -->
    <div class="col-lg-8">
        <div class="card h-100 border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="text-light m-0">30-Day Trend Analysis (USD to {{ $selectedCurrency }})</h5>
                @if($selectedCurrentRate)
                    <span class="badge bg-primary-blue fs-6 border border-dark">Current: {{ number_format($selectedCurrentRate->rate, 4) }}</span>
                @endif
            </div>
            <div class="card-body">
                <canvas id="mainCurrencyChart" style="height: 350px; width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Currencies Mini Charts -->
    <div class="col-lg-4">
        <div class="card h-100 border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent">
                <h5 class="text-light m-0">Major World Currencies</h5>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush bg-transparent">
                    @foreach($topCurrencies as $currency)
                        @php
                            $rateData = $rates->where('target_currency', $currency)->first();
                        @endphp
                        @if($rateData)
                        <li class="list-group-item bg-transparent text-light border-secondary py-3 hover-effect">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0 fw-bold">{{ $currency }}</h5>
                                    <small class="text-muted">1 USD =</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="m-0 text-accent">{{ number_format($rateData->rate, 4) }}</h5>
                                </div>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <!-- All Exchange Rates Table -->
    <div class="col-12 mt-4">
        <div class="card border-secondary shadow-lg">
            <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="text-light m-0"><i class="fas fa-list text-primary-blue me-2"></i>Comprehensive Exchange Rates</h5>
                <span class="badge bg-secondary">Data from ExchangeRate API</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-dark table-hover table-striped mb-0 text-center">
                        <thead class="sticky-top" style="z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Currency Code</th>
                                <th>Exchange Rate (vs USD)</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rates as $index => $rate)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-accent">{{ $rate->target_currency }}</td>
                                    <td>{{ number_format($rate->rate, 4) }}</td>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($rate->fetched_at)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-effect:hover {
        background-color: rgba(255,255,255,0.05) !important;
        cursor: pointer;
    }
    .text-accent { color: var(--accent-blue) !important; }
</style>

<!-- Chart.js Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('mainCurrencyChart').getContext('2d');
        
        const dates = {!! json_encode($dates) !!};
        const selectedData = {!! json_encode($selectedHistoryData) !!};
        const selectedLabel = '{{ $selectedCurrency }}';
        
        // Custom gradient for the chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(10, 132, 255, 0.5)'); // Primary Blue
        gradient.addColorStop(1, 'rgba(10, 132, 255, 0.0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: `USD to ${selectedLabel} Rate`,
                    data: selectedData,
                    borderColor: '#0A84FF',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0A84FF',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleColor: '#fff',
                        bodyColor: '#CBD5E1',
                        borderColor: '#1e293b',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#1e293b', drawBorder: false },
                        ticks: { color: '#CBD5E1', maxTicksLimit: 10 }
                    },
                    y: {
                        grid: { color: '#1e293b', drawBorder: false },
                        ticks: { color: '#CBD5E1' }
                    }
                }
            }
        });
    });
</script>
@endsection
