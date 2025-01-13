@extends('layouts.master')

@section('content')
    <div class="container-fluid mb-3">
        <div class="row mb-4 justify-content-center" style="display: flex; flex-wrap: wrap; align-items: stretch;">
            <!-- Main Rectangular Statistic (Jumlah Aduan) -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
                <div class="bg-primary text-white p-4 rounded shadow"
                    style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                    <h5 class="text-center fw-bold">JUMLAH ADUAN ICT</h5>
                    <h1 class="display-5 text-center">{{ $totalAduan }}</h1>
                </div>
            </div>

            <!-- Donut Charts with Cards (4 charts) -->
            @php
                $charts = [
                    [
                        'id' => 'aduanCompletedChart',
                        'label' => 'COMPLETED & VERIFIED',
                        'value' => $aduanCompleted,
                        'color' => 'rgba(40, 167, 69, 0.7)',
                        'percent' => $percentAduanCompleted,
                    ],
                    [
                        'id' => 'inProgressChart',
                        'label' => 'IN PROGRESS',
                        'value' => $inProgress,
                        'color' => 'rgba(255, 193, 7, 0.7)',
                        'percent' => $percentInProgress,
                    ],
                    [
                        'id' => 'cancelledChart',
                        'label' => 'CANCELLED',
                        'value' => $cancelled,
                        'color' => 'rgba(220, 53, 69, 0.7)',
                        'percent' => $percentCancelled,
                    ],
                    [
                        'id' => 'closedChart',
                        'label' => 'CLOSED',
                        'value' => $closed,
                        'color' => 'rgba(108, 117, 125, 0.7)',
                        'percent' => $percentClosed,
                    ],
                ];
            @endphp

            @foreach ($charts as $chart)
                <div class="col-lg-2 col-md-3 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
                    <div class="card shadow border-0 rounded"
                        style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                            <canvas id="{{ $chart['id'] }}"></canvas>
                            <h5 class="fw-bold mt-2" style="font-size: 14px; text-align: center;">{{ $chart['label'] }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Campus Cards -->
        <h4 class="text-center mb-4">KAMPUS</h4>
        <div class="row justify-content-center">
            @php
                $campuses = [
                    ['label' => 'SAMARAHAN', 'value' => $samarahan, 'percent' => $percentSamarahan],
                    ['label' => 'SAMARAHAN 2', 'value' => $samarahan2, 'percent' => $percentSamarahan2],
                    ['label' => 'MUKAH', 'value' => $mukah, 'percent' => $percentMukah],
                ];
            @endphp

            @foreach ($campuses as $campus)
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
                    <div class="card shadow border-0 rounded"
                        style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                            <h5 class="fw-bold text-primary text-center">{{ $campus['label'] }}</h5>
                            <h1 class="display-5 text-center">{{ $campus['value'] }}</h1>
                            <h6 class="text-muted text-center">{{ $campus['percent'] }}%</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row justify-content-center">
            <!-- Complainent Cards -->
            @php
                $Complainents = [
                    ['label' => 'STAFF', 'value' => $staff, 'percent' => $percentStaff],
                    ['label' => 'STUDENT', 'value' => $student, 'percent' => $percentStudent],
                    ['label' => 'GUEST', 'value' => $guest, 'percent' => $percentGuest],
                ];
            @endphp

            <div class="col-lg-6 col-md-12 col-sm-12">
                <h4 class="text-center mb-4">PENGADU</h4>
                <div class="row">
                    @foreach ($Complainents as $complainent)
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 d-flex">
                            <div class="card shadow border-0 rounded w-100 d-flex" style="min-height: 200px;">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                    <h5 class="fw-bold text-primary text-center">{{ $complainent['label'] }}</h5>
                                    <h1 class="display-5 text-center">{{ $complainent['value'] }}</h1>
                                    <h6 class="text-muted text-center">{{ $complainent['percent'] }}%</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12">
                <h4 class="text-center mb-4">MASA TINDAK BALAS SELESAI (HARI)</h4>
                <!-- Response Days Statistics -->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3 d-flex">
                        <div class="card shadow border-0 rounded w-100 d-flex" style="min-height: 200px;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                <h5 class="fw-bold text-primary text-center"> ≤ 3 DAYS </h5>
                                <h1 class="display-5 text-center">{{ $responseDaysLessThanOrEqual3 }}</h1>
                                <h6 class="text-muted text-center">{{ $percentResponseLessThanOrEqual3 }}%</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3 d-flex">
                        <div class="card shadow border-0 rounded w-100 d-flex" style="min-height: 200px;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                <h5 class="fw-bold text-primary text-center">> 3 DAYS</h5>
                                <h1 class="display-5 text-center">{{ $responseDaysMoreThan3 }}</h1>
                                <h6 class="text-muted text-center">{{ $percentResponseMoreThan3 }}%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart Data
        const totalAduan = {{ $totalAduan }};
        const chartsData = [{
                elementId: 'aduanCompletedChart',
                value: {{ $aduanCompleted }},
                percent: {{ $percentAduanCompleted }},
                label: 'Completed & Verified',
                color: 'rgba(40, 167, 69, 0.7)',
            },
            {
                elementId: 'inProgressChart',
                value: {{ $inProgress }},
                percent: {{ $percentInProgress }},
                label: 'In Progress',
                color: 'rgba(255, 193, 7, 0.7)',
            },
            {
                elementId: 'cancelledChart',
                value: {{ $cancelled }},
                percent: {{ $percentCancelled }},
                label: 'Cancelled',
                color: 'rgba(220, 53, 69, 0.7)',
            },
            {
                elementId: 'closedChart',
                value: {{ $closed }},
                percent: {{ $percentClosed }},
                label: 'Closed',
                color: 'rgba(108, 117, 125, 0.7)',
            },
        ];

        // Plugin to display text in the center
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart) {
                const {
                    width
                } = chart;
                const {
                    ctx
                } = chart;
                const dataset = chart.data.datasets[0];
                const totalValue = dataset.data[0];
                const percentage = chartsData.find(data => data.elementId === chart.canvas.id).percent;

                ctx.save();
                ctx.font = 'bold 18px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                ctx.fillStyle = 'black';
                ctx.fillText(`${totalValue}`, width / 2, chart.chartArea.height / 2 - 10);

                ctx.font = 'bold 18px Arial';
                ctx.fillStyle = '#666';
                ctx.fillText(`${percentage}%`, width / 2, chart.chartArea.height / 2 + 15);
                ctx.restore();
            },
        };

        // Register the plugin
        Chart.register(centerTextPlugin);

        // Render Charts
        chartsData.forEach(chart => {
            const ctx = document.getElementById(chart.elementId).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [chart.label, 'Others'],
                    datasets: [{
                        data: [chart.value, totalAduan - chart.value],
                        backgroundColor: [chart.color, 'rgba(233, 236, 239, 0.7)'],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                },
            });
        });
    </script>
@endsection
