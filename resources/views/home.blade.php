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
                            @for ($y = 2020; $y <= now()->year; $y++)
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
                    <h1 class="display-5 text-center">{{ $card['value'] }}</h1>
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
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <!-- Canvas for the chart -->
                        <canvas id="aduanChart" style="max-height: 400px; max-width: 100%;"></canvas>
                        <!-- Fallback text for 'No Data' -->
                        <p id="noDataMessage" style="display: none; text-align: center; width: 100%;">Tiada rekod</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="table-responsive">
                            <table id="categoryTable" class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">KATEGORI</th>
                                        <th class="text-center">SUBKATEGORI</th>
                                        <th class="text-center">JUMLAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($allCategories) > 0)
                                    @foreach ($allCategories as $categoryData)
                                    <tr>
                                        <td>{{ ($loop->index + 1) }}</td>
                                        <td style="word-wrap: break-word; white-space: normal;">{{ $categoryData['category'] }}</td>
                                        <td style="word-wrap: break-word; white-space: normal;">{{ $categoryData['subcategory'] }}</td>
                                        <td class="text-center">{{ $categoryData['count'] }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4">Tiada rekod</td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Jumlah Keseluruhan</th>
                                        <th class="text-center">{{ $totalCountAllCategories }}</th>
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
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
        <h4 class="text-center mb-4">JUMLAH ADUAN MENGIKUT KATEGORI PENGADU & TEMPOH TINDAK BALAS SELESAI (HARI)</h4>
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <canvas id="complainantChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
        <h4 class="text-center mb-4">JUMLAH BULANAN ADUAN MENGIKUT KATEGORI ADUAN</h4>
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <canvas id="aduanMonthCatChart" width="400" height="200"></canvas>
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

<!-- Kategori Aduan -->
<script>
    // Use the passed data from PHP
    const aduanCategoryData = <?php echo json_encode($aduanCategoryData); ?>;

    // Filter out the "Lain-lain" category
    const filteredAduanCategoryData = aduanCategoryData.filter(item => item.category !== 'Lain-lain');

    // Check if there's no data
    const noDataMessageElement = document.getElementById('noDataMessage');
    const cardBodyElement = document.querySelector('.card-body');
    const canvasElement = document.getElementById('aduanChart');

    if (filteredAduanCategoryData.length === 0) {
        // Show the 'No Data' message and adjust the card and canvas height
        noDataMessageElement.style.display = 'block';
        cardBodyElement.style.height = '150px'; // Reduce the height when there's no data
        canvasElement.style.display = 'none'; // Hide the canvas
    } else {
        // Show the canvas and prepare labels and data for the chart
        noDataMessageElement.style.display = 'none';
        canvasElement.style.display = 'block';

        const labels = filteredAduanCategoryData.map(item => item.category);
        const data = filteredAduanCategoryData.map(item => item.count);

        // Horizontal Rounded Bar Chart Configuration
        const ctx = canvasElement.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Aduan',
                    data: data,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#8A2BE2', '#00CED1', '#FFD700', '#DC143C'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#8A2BE2', '#00CED1', '#FFD700', '#DC143C'
                    ],
                    borderRadius: 10,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10, // Add left padding
                        right: 90, // Add right padding to allow space for labels
                        top: 10,
                        bottom: 10
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: true,
                        ticks: {
                            color: '#000',
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(2);
                                return `${context.raw} (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${value} (${percentage}%)`;
                        },
                        offset: 1 // Ensure labels are not cut off
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
</script>

<!-- Jumlah aduan x pengadu x respon day -->
<script>
    // Data from the server
    const complainantData = <?php echo json_encode($complainantData); ?>;
    const totalComplaints = <?php echo json_encode($totalComplaints); ?>;
    const percentageData = <?php echo json_encode($percentageData); ?>;

    // Complainant Chart (Response days by complainant category)
    const complainantCtx = document.getElementById('complainantChart').getContext('2d');

    new Chart(complainantCtx, {
        type: 'bar', // Stacked bar chart
        data: {
            labels: ['0', '1', '2', '3', '>3'],
            datasets: [{
                    label: 'STAFF',
                    data: [
                        complainantData.STAFF['0'] || 0,
                        complainantData.STAFF['1'] || 0,
                        complainantData.STAFF['2'] || 0,
                        complainantData.STAFF['3'] || 0,
                        complainantData.STAFF['>3'] || 0
                    ],
                    backgroundColor: '#FF6384',
                },
                {
                    label: 'STUDENT',
                    data: [
                        complainantData.STUDENT['0'] || 0,
                        complainantData.STUDENT['1'] || 0,
                        complainantData.STUDENT['2'] || 0,
                        complainantData.STUDENT['3'] || 0,
                        complainantData.STUDENT['>3'] || 0
                    ],
                    backgroundColor: '#36A2EB',
                },
                {
                    label: 'GUEST',
                    data: [
                        complainantData.GUEST['0'] || 0,
                        complainantData.GUEST['1'] || 0,
                        complainantData.GUEST['2'] || 0,
                        complainantData.GUEST['3'] || 0,
                        complainantData.GUEST['>3'] || 0
                    ],
                    backgroundColor: '#FFCE56',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            const day = tooltipItems[0].label;
                            const total = totalComplaints[day];
                            return `Day ${day}: Total ${total}`;
                        },
                    },
                },
                datalabels: {
                    display: true,
                    color: 'black',
                    align: 'top', // Align label at the top of the bar
                    anchor: 'end', // Anchor label at the end of the bar
                    font: {
                        weight: 'bold',
                        size: 12,
                    },
                    formatter: function(value, context) {
                        const day = context.chart.data.labels[context.dataIndex];

                        // Sum up the values for the current response day (across all categories)
                        const totalForDay = context.chart.data.datasets.reduce((sum, dataset) => {
                            return sum + (dataset.data[context.dataIndex] || 0);
                        }, 0);

                        // Get the percentage for that response day
                        const percentage = percentageData[day].toFixed(2); // Percentage based on totalAduan

                        // Only display the label for the last dataset, as this is where we want the total and percentage
                        if (context.datasetIndex === context.chart.data.datasets.length - 1) {
                            return `${totalForDay} (${percentage}%)`; // Return total and percentage at the end of the stacked bar
                        }

                        return ''; // Do not show any labels for the other datasets (STAFF, STUDENT)
                    },
                },
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Tempoh Tindak Balas Selesai (Hari)',
                    },
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Jumlah Aduan',
                    },
                },
            },
        },
        plugins: [ChartDataLabels],
    });
</script>

<!-- Aduan x Category x Month -->
<script>
    const ctx = document.getElementById('aduanMonthCatChart').getContext('2d');
    const aduanMonthCategoryChart = <?php echo json_encode($aduanMonthCategoryChart); ?>;
    const categories = <?php echo json_encode($categories); ?>;

    const labels = aduanMonthCategoryChart.map(data => `${data.month}`);
    const colors = [
        'rgba(255, 99, 132, 0.8)', // Bold red
        'rgba(54, 162, 235, 0.8)', // Bold blue
        'rgba(255, 206, 86, 0.8)', // Bold yellow
        'rgba(75, 192, 192, 0.8)', // Bold teal
        'rgba(153, 102, 255, 0.8)', // Bold purple
        'rgba(255, 159, 64, 0.8)', // Bold orange
        'rgba(199, 199, 199, 0.8)' // Bold gray
    ];

    const datasets = categories.map((category, index) => {
        return {
            label: category,
            data: aduanMonthCategoryChart.map(data => data[category] || 0),
            backgroundColor: colors[index % colors.length],
        };
    });

    const aduanMonthCatChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                },
            },
        },
    });
</script>

<!-- Datatable -->
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            "ordering": true,
            "info": false,
            "searching": false,
            "scrollY": "275px",
            "scrollCollapse": true,
            "paging": false
        });
    });
</script>

@endsection