@extends('layouts.master')

@section('content')
<div class="container-fluid mb-3">
    <div class="row mb-4 justify-content-center" style="display: flex; flex-wrap: wrap; align-items: stretch;">
        <!-- Main Rectangular Statistic (Jumlah Aduan) -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
            <div class="bg-primary text-white p-4 rounded shadow" style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                <h5 class="text-center fw-bold">JUMLAH ADUAN ICT</h5>
                <h1 class="display-5 text-center">{{ $totalAduan }}</h1>
            </div>
        </div>

        <!-- Donut Charts with Cards (4 charts) -->
        @php
        $charts = [
            ['id' => 'aduanCompletedChart', 'label' => 'COMPLETED & VERIFIED', 'value' => $aduanCompleted, 'color' => 'rgba(40, 167, 69, 0.7)', 'percent' => $percentAduanCompleted],
            ['id' => 'inProgressChart', 'label' => 'IN PROGRESS', 'value' => $inProgress, 'color' => 'rgba(255, 193, 7, 0.7)', 'percent' => $percentInProgress],
            ['id' => 'cancelledChart', 'label' => 'CANCELLED', 'value' => $cancelled, 'color' => 'rgba(220, 53, 69, 0.7)', 'percent' => $percentCancelled],
            ['id' => 'closedChart', 'label' => 'CLOSED', 'value' => $closed, 'color' => 'rgba(108, 117, 125, 0.7)', 'percent' => $percentClosed],
        ];
        @endphp

        @foreach($charts as $chart)
        <div class="col-lg-2 col-md-3 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
            <div class="card shadow border-0 rounded" style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <canvas id="{{ $chart['id'] }}"></canvas>
                    <h5 class="fw-bold mt-2" style="font-size: 14px; text-align: center;">{{ $chart['label'] }}</h5>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Data
    const totalAduan = {{ $totalAduan }};
    const chartsData = [
        {
            elementId: 'aduanCompletedChart',
            value: {{ $aduanCompleted }},
            percent: {{ $percentAduanCompleted }},
            label: 'Completed & Verified',
            color: 'rgba(40, 167, 69, 0.7)', // Bootstrap success color
        },
        {
            elementId: 'inProgressChart',
            value: {{ $inProgress }},
            percent: {{ $percentInProgress }},
            label: 'In Progress',
            color: 'rgba(255, 193, 7, 0.7)', // Bootstrap warning color
        },
        {
            elementId: 'cancelledChart',
            value: {{ $cancelled }},
            percent: {{ $percentCancelled }},
            label: 'Cancelled',
            color: 'rgba(220, 53, 69, 0.7)', // Bootstrap danger color
        },
        {
            elementId: 'closedChart',
            value: {{ $closed }},
            percent: {{ $percentClosed }},
            label: 'Closed',
            color: 'rgba(108, 117, 125, 0.7)', // Bootstrap secondary color
        },
    ];

    // Plugin to display text in the center
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            const { width } = chart;
            const { ctx } = chart;
            const dataset = chart.data.datasets[0];
            const totalValue = dataset.data[0];
            const percentage = chartsData.find(data => data.elementId === chart.canvas.id).percent;

            ctx.save();
            ctx.font = 'bold 18px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Actual Value
            ctx.fillStyle = 'black';
            ctx.fillText(`${totalValue}`, width / 2, chart.chartArea.height / 2 - 10);

            // Percentage
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
                labels: [chart.label, 'Lain-lain'], // Updated "Remaining" to "Unresolved"
                datasets: [{
                    data: [chart.value, totalAduan - chart.value],
                    backgroundColor: [chart.color, 'rgba(233, 236, 239, 0.7)'], // Second color for unresolved
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                cutout: '70%', 
                plugins: {
                    legend: { display: false },
                },
            },
        });
    });
</script>

@endsection
