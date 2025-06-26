@extends('layouts.app') <!-- Используем ваш основной layout -->

@section('content')
<div class="container">
    <h1>📊 Статистика QR-кодов</h1>

    <!-- Общее количество -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Всего QR-кодов сгенерировано</h5>
            <p class="display-4">{{ $totalQRCodes }}</p>
        </div>
    </div>

    <!-- Графики -->
    <div class="row">
        <!-- Распределение по типам -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Типы QR-кодов</h5>
                    <canvas id="typesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- График по дням -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Активность по дням</h5>
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подключаем Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Круговая диаграмма (типы QR-кодов)
    const typesCtx = document.getElementById('typesChart').getContext('2d');
    new Chart(typesCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($typesDistribution->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($typesDistribution->pluck('total')) !!},
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                ]
            }]
        }
    });

    // Линейный график (активность по дням)
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyStats->pluck('date')) !!},
            datasets: [{
                label: 'QR-кодов в день',
                data: {!! json_encode($dailyStats->pluck('total')) !!},
                borderColor: '#36A2EB',
                fill: false
            }]
        }
    });
</script>
@endsection