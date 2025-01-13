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

        <!-- Cards replacing Donut Charts (4 cards) -->
        @php
        $cards = [
        [
        'label' => 'COMPLETED & VERIFIED',
        'value' => $aduanCompleted,
        'percent' => $percentAduanCompleted,
        ],
        [
        'label' => 'IN PROGRESS',
        'value' => $inProgress,
        'percent' => $percentInProgress,
        ],
        [
        'label' => 'CANCELLED',
        'value' => $cancelled,
        'percent' => $percentCancelled,
        ],
        [
        'label' => 'CLOSED',
        'value' => $closed,
        'percent' => $percentClosed,
        ],
        ];
        @endphp

        @foreach ($cards as $card)
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
            <!-- Response Days Statistics -->
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>KATEGORI</th>
                                        <th>JUMLAH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($aduanCategoryCounts) > 0)
                                    @foreach ($aduanCategoryCounts as $categoryData)
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
                                        <th>{{ array_sum(array_column($aduanCategoryCounts, 'count')) }}</th>
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