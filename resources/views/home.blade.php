@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <form action="{{ route('home') }}" method="GET" id="searchForm">
                <div class="d-flex flex-wrap justify-content-end">
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="campus" class="form-select ms-2 rounded" id="campusFilter">
                            <option value="all" {{ request('campus', 'all') == 'all' ? 'selected' : '' }}>Semua Kampus</option>
                            @foreach ($campusFilter as $campus)
                            <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>
                                {{ $campus }}
                            </option>
                            @endforeach
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
                        <select name="complainent_category" class="form-select ms-2 rounded" id="complainentCategoryFilter">
                            <option value="all" {{ request('complainent_category', 'all') == 'all' ? 'selected' : '' }}>Semua Kategori Pengadu</option>
                            @foreach ($complainentCategoryFilter as $category)
                            <option value="{{ $category }}" {{ request('complainent_category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="category" class="form-select ms-2 rounded" id="categoryFilter">
                            <option value="all" {{ request('category', 'all') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                            @foreach ($categoryFilter as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="aduan_category" class="form-select ms-2 rounded" id="aduanCategoryFilter">
                            <option value="all" {{ request('aduan_category', 'all') == 'all' ? 'selected' : '' }}>Semua Kategori Aduan</option>
                            @foreach ($aduanCategoryFilter as $category)
                            <option value="{{ $category }}" {{ request('aduan_category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 ms-2 col-12 col-md-auto">
                        <select name="aduan_status" class="form-select ms-2 rounded" id="aduanStatusFilter">
                            <option value="all" {{ request('aduan_status', 'all') == 'all' ? 'selected' : '' }}>Semua Aduan Status</option>
                            @foreach ($aduanStatusFilter as $aduanStatus)
                            <option value="{{ $aduanStatus }}" {{ request('aduan_status') == $aduanStatus ? 'selected' : '' }}>
                                {{ $aduanStatus }}
                            </option>
                            @endforeach
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

    <!-- Complainent Cards -->
    <div class="row justify-content-center">
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

    <!-- Kategori Aduan -->
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

    <!-- Jumlah aduan x kategori pengadu x tempoh respon -->
    <h4 class="text-center mb-4">JUMLAH ADUAN MENGIKUT KATEGORI PENGADU & TEMPOH TINDAK BALAS SELESAI</h4>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <canvas id="complainantChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <canvas id="responseDaysChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('aduanStatusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('campusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('complainentCategoryFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('categoryFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('aduanCategoryFilter').addEventListener('change', function() {
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

<!-- 4 Main Cards -->
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

<!-- Kategori Aduan -->
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
                },
                datalabels: {
                    color: '#000', // Text color
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${percentage}%`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

<!-- Jumlah aduan x pengadu x respon day -->
<script>
    // Data from the server
    const complainantData = @json($complainantData);
    const responseDaysData = @json($responseDaysData);

    // Function to display total on bars
    const displayTotal = {
        id: 'displayTotal',
        beforeDraw(chart) {
            const ctx = chart.ctx;
            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index];
                    ctx.fillStyle = '#000';
                    ctx.font = '12px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(value, bar.x, bar.y - 5); // Position above the bar
                });
            });
        }
    };

    // Complainant Chart
    const complainantCtx = document.getElementById('complainantChart').getContext('2d');
    new Chart(complainantCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(complainantData),
            datasets: [{
                data: Object.values(complainantData),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Disable legend
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Kategori pengadu'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah aduan'
                    }
                }
            }
        },
        plugins: [displayTotal]
    });

    // Response Days Chart
    const responseDaysCtx = document.getElementById('responseDaysChart').getContext('2d');
    new Chart(responseDaysCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(responseDaysData),
            datasets: [{
                data: Object.values(responseDaysData),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Disable legend
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tempoh tindak balas selesai (hari)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Aduan'
                    }
                }
            }
        },
        plugins: [displayTotal]
    });
</script>

@endsection