@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="text-light mb-3"><i class="fas fa-balance-scale text-primary-blue me-2"></i>Country Comparison Engine</h4>
        
        <!-- Selection Area -->
        <div class="card border-secondary shadow-lg mb-4">
            <div class="card-body">
                <form id="compareForm" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label text-muted small text-uppercase">Country A (Red Corner)</label>
                        <select name="country1_id" id="country1" class="form-select border-secondary text-light shadow-none" style="background-color: var(--dark-bg);">
                            <option value="">-- Select First Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 text-center">
                        <div class="d-inline-flex justify-content-center align-items-center rounded-circle border border-secondary" style="width: 50px; height: 50px; background: rgba(15, 23, 42, 0.5);">
                            <h5 class="m-0 text-accent fw-bold">VS</h5>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label text-muted small text-uppercase">Country B (Blue Corner)</label>
                        <select name="country2_id" id="country2" class="form-select border-secondary text-light shadow-none" style="background-color: var(--dark-bg);">
                            <option value="">-- Select Second Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingIndicator" class="text-center py-5 d-none">
            <i class="fas fa-circle-notch fa-spin text-primary-blue fa-3x mb-3"></i>
            <h5 class="text-muted">Analyzing Data...</h5>
        </div>

        <!-- Comparison Result Area -->
        <div id="comparisonResult" class="d-none">
            
            <!-- Radar Chart Row -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="card border-secondary shadow-lg">
                        <div class="card-header border-secondary bg-transparent text-center">
                            <h6 class="m-0 text-light"><i class="fas fa-spider text-accent me-2"></i>Risk Radar Analysis</h6>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <canvas id="radarChart" style="height: 350px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3-Column VS Layout -->
            <div class="card border-secondary shadow-lg">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Country 1 (Left) -->
                        <div class="col-4 text-center py-4 border-end border-secondary" style="background: rgba(255, 59, 48, 0.05);">
                            <h3 id="c1-name" class="text-light fw-bold mb-3">Country A</h3>
                            <div class="mb-4">
                                <img id="c1-flag" src="" alt="Flag" class="border border-secondary shadow-sm rounded" style="height: 60px; object-fit: cover;">
                            </div>
                            
                            <h4 id="c1-score" class="text-danger fw-bold">--</h4>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c1-pop" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c1-gdp" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c1-inf" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c1-temp" class="text-light fs-5">--</p>
                        </div>

                        <!-- Parameters (Center) -->
                        <div class="col-4 text-center py-4">
                            <h5 class="text-muted text-uppercase mb-5" style="letter-spacing: 2px;">Parameters</h5>
                            <br><br>
                            <h6 class="text-muted text-uppercase mb-0">Total Risk Score</h6>
                            <hr class="border-secondary my-3 w-75 mx-auto">
                            <h6 class="text-muted text-uppercase mb-0">Population</h6>
                            <hr class="border-secondary my-3 w-75 mx-auto">
                            <h6 class="text-muted text-uppercase mb-0">Latest GDP</h6>
                            <hr class="border-secondary my-3 w-75 mx-auto">
                            <h6 class="text-muted text-uppercase mb-0">Inflation Rate</h6>
                            <hr class="border-secondary my-3 w-75 mx-auto">
                            <h6 class="text-muted text-uppercase mb-0">Live Weather</h6>
                        </div>

                        <!-- Country 2 (Right) -->
                        <div class="col-4 text-center py-4 border-start border-secondary" style="background: rgba(10, 132, 255, 0.05);">
                            <h3 id="c2-name" class="text-light fw-bold mb-3">Country B</h3>
                            <div class="mb-4">
                                <img id="c2-flag" src="" alt="Flag" class="border border-secondary shadow-sm rounded" style="height: 60px; object-fit: cover;">
                            </div>
                            
                            <h4 id="c2-score" class="text-primary-blue fw-bold">--</h4>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c2-pop" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c2-gdp" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c2-inf" class="text-light fs-5">--</p>
                            <hr class="border-secondary my-3 w-50 mx-auto">
                            <p id="c2-temp" class="text-light fs-5">--</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let radarChartInstance = null;

    const select1 = document.getElementById('country1');
    const select2 = document.getElementById('country2');

    function triggerComparison() {
        const id1 = select1.value;
        const id2 = select2.value;

        if (id1 && id2 && id1 !== id2) {
            document.getElementById('comparisonResult').classList.add('d-none');
            document.getElementById('loadingIndicator').classList.remove('d-none');

            fetch('{{ route('compare.fetch') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ country1_id: id1, country2_id: id2 })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('loadingIndicator').classList.add('d-none');
                document.getElementById('comparisonResult').classList.remove('d-none');
                renderComparison(data);
            })
            .catch(err => {
                console.error(err);
                document.getElementById('loadingIndicator').classList.add('d-none');
                alert('Error fetching comparison data.');
            });
        }
    }

    select1.addEventListener('change', triggerComparison);
    select2.addEventListener('change', triggerComparison);

    function formatNumber(num) {
        if (!num || isNaN(num)) return 'N/A';
        return new Intl.NumberFormat('en-US').format(num);
    }

    function renderComparison(data) {
        const c1 = data.country1;
        const c2 = data.country2;

        // Render Names & Flags
        document.getElementById('c1-name').innerText = c1.details.name;
        document.getElementById('c2-name').innerText = c2.details.name;
        document.getElementById('c1-flag').src = `https://flagcdn.com/w160/${c1.details.code.toLowerCase()}.png`;
        document.getElementById('c2-flag').src = `https://flagcdn.com/w160/${c2.details.code.toLowerCase()}.png`;

        // Render Table Data
        document.getElementById('c1-score').innerText = c1.details.risk_score ? parseFloat(c1.details.risk_score.total_score).toFixed(1) : 'N/A';
        document.getElementById('c2-score').innerText = c2.details.risk_score ? parseFloat(c2.details.risk_score.total_score).toFixed(1) : 'N/A';

        document.getElementById('c1-pop').innerText = formatNumber(c1.details.population);
        document.getElementById('c2-pop').innerText = formatNumber(c2.details.population);

        document.getElementById('c1-gdp').innerText = c1.economy.gdp !== 'N/A' ? '$' + formatNumber(c1.economy.gdp) : 'N/A';
        document.getElementById('c2-gdp').innerText = c2.economy.gdp !== 'N/A' ? '$' + formatNumber(c2.economy.gdp) : 'N/A';

        document.getElementById('c1-inf').innerText = c1.economy.inflation !== 'N/A' ? parseFloat(c1.economy.inflation).toFixed(2) + '%' : 'N/A';
        document.getElementById('c2-inf').innerText = c2.economy.inflation !== 'N/A' ? parseFloat(c2.economy.inflation).toFixed(2) + '%' : 'N/A';

        document.getElementById('c1-temp').innerHTML = c1.weather ? `${c1.weather.temperature_2m}°C <i class="fas fa-cloud-sun ms-1 text-muted"></i>` : 'N/A';
        document.getElementById('c2-temp').innerHTML = c2.weather ? `${c2.weather.temperature_2m}°C <i class="fas fa-cloud-sun ms-1 text-muted"></i>` : 'N/A';

        // Render Radar Chart
        if (radarChartInstance) {
            radarChartInstance.destroy();
        }

        const ctx = document.getElementById('radarChart').getContext('2d');
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(51, 65, 85, 0.5)';

        const getScores = (riskObj) => {
            if(!riskObj) return [0,0,0,0];
            return [riskObj.weather_risk, riskObj.inflation_risk, riskObj.news_risk, riskObj.currency_risk];
        };

        radarChartInstance = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Weather Risk', 'Inflation Risk', 'Geopolitical Risk', 'Currency Risk'],
                datasets: [
                    {
                        label: c1.details.name,
                        data: getScores(c1.details.risk_score),
                        backgroundColor: 'rgba(255, 59, 48, 0.2)',
                        borderColor: '#ff3b30',
                        pointBackgroundColor: '#ff3b30',
                        borderWidth: 2
                    },
                    {
                        label: c2.details.name,
                        data: getScores(c2.details.risk_score),
                        backgroundColor: 'rgba(10, 132, 255, 0.2)',
                        borderColor: '#0a84ff',
                        pointBackgroundColor: '#0a84ff',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: { color: '#e2e8f0', font: { size: 13 } },
                        ticks: { display: false, min: 0, max: 100 }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endsection
