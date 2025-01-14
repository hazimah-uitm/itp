@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <form action="{{ route('home') }}" method="GET" id="searchForm">
                <div class="d-flex flex-wrap justify-content-end">
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="campus" class="form-select ms-2 rounded" id="campusFilter">
                            <option value="">Semua Kampus</option>
                            <option value="SAMARAHAN" {{ request('campus') == 'SAMARAHAN' ? 'selected' : '' }}>Samarahan</option>
                            <option value="SAMARAHAN 2" {{ request('campus') == 'SAMARAHAN 2' ? 'selected' : '' }}>Samarahan 2</option>
                            <option value="MUKAH" {{ request('campus') == 'MUKAH' ? 'selected' : '' }}>Mukah</option>
                        </select>
                    </div>
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="month" class="form-select ms-2 rounded" id="monthFilter">
                            <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="year" class="form-select ms-2 rounded" id="yearFilter">
                            <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <button type="button" class="btn btn-secondary ms-1 rounded" id="resetButton">
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4 justify-content-center" style="display: flex; flex-wrap: wrap; align-items: stretch;">
        <!-- Main Rectangular Statistic (Jumlah Aduan) -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
            <div class="bg-primary text-white p-4 rounded shadow"
                style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                <h5 class="text-center fw-bold">JUMLAH ADUAN ICT</h5>
                <h1 class="display-5 text-center">{{ $totalAduan }}</h1>
            </div>
        </div>

        <!-- 4 Main Cards -->
        @php
        $cards = [
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

        @foreach ($cards as $index => $card)
        <div class="col-lg-2 col-md-3 col-sm-12 mb-3" style="display: flex; align-items: stretch;">
            <div class="card shadow border-0 rounded"
                style="display: flex; flex-direction: column; justify-content: center; align-items: stretch; height: 100%; width: 100%;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="fw-bold text-primary text-center">{{ $card['label'] }}</h5>
                    <canvas id="chart-{{ $index }}" width="100" height="100"></canvas>
                    <h6 class="text-center mt-2">{{ $card['value'] }}</h6>
                    <h6 class="text-muted text-center">{{ $card['percent'] }}%</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = @json($cards);

            cards.forEach((card, index) => {
                const ctx = document.getElementById(`chart-${index}`).getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total', 'Others'],
                        datasets: [{
                            data: [card.percent, 100 - card.percent],
                            backgroundColor: [card.color, '#e0e0e0'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>

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

    <h4 class="text-center mb-4">KATEGORI ADUAN</h4>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card" id="aduanCard" style="height: auto;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <!-- Canvas for the chart -->
                        <canvas id="aduanChart" style="max-height: 405px; max-width: 750px;"></canvas>
                        <!-- Fallback text for 'No Data' -->
                        <p id="noDataMessage" style="display: none; text-align: center; width: 100%;">Tiada rekod</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Use the passed data from PHP
            const aduanCategoryData = <?php echo json_encode($aduanCategoryData); ?>;

            // Check if aduanCategoryData is null or empty, set default values if so
            const safeAduanCategoryData = aduanCategoryData && aduanCategoryData.length > 0 ? aduanCategoryData : [{
                category: 'No Data',
                count: 0,
                percentage: 0
            }];

            // Dynamically adjust the content visibility based on data availability
            const aduanCard = document.getElementById('aduanCard');
            const noDataMessage = document.getElementById('noDataMessage');
            const aduanChart = document.getElementById('aduanChart');

            if (safeAduanCategoryData.length === 1 && safeAduanCategoryData[0].count === 0) {
                // When there's no data, show "No Data Available" and hide the chart
                aduanChart.style.display = 'none'; // Hide the canvas
                noDataMessage.style.display = 'block'; // Show the "No Data" message
            } else {
                // Display the chart when there is data and hide the "No Data" message
                aduanChart.style.display = 'block'; // Show the chart
                noDataMessage.style.display = 'none'; // Hide the "No Data" message
            }

            // Prepare labels and data for the chart
            const labels = safeAduanCategoryData.map(item => item.category);
            const data = safeAduanCategoryData.map(item => item.count);

            // Doughnut Chart Configuration
            const ctx = document.getElementById('aduanChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Aduan Categories',
                        data: data,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ],
                        hoverBackgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Disable maintaining aspect ratio
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(2);
                                    return `${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="table-responsive" style="max-height: 610px; overflow-y: auto;">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>KATEGORI</th>
                                        <th>JUMLAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($aduanCategoryData) > 0)
                                    @foreach ($aduanCategoryData as $categoryData)
                                    <tr>
                                        <td class="text-center">{{ ($loop->index + 1) }}</td>
                                        <td>{{ $categoryData['category'] }}</td>
                                        <td>{{ $categoryData['count'] }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <td colspan="3">Tiada rekod</td>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Jumlah Keseluruhan</th>
                                        <th>{{ array_sum(array_column($aduanCategoryData, 'count')) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('campusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('monthFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('yearFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('resetButton').addEventListener('click', function() {
            // Redirect to the base route to clear query parameters
            window.location.href = "{{ route('home') }}";
        });

    });
</script>
@endsection