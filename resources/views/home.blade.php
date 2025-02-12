@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <form action="{{ route('home') }}" method="GET" id="searchForm">
                <!-- Filter Button -->
                <div class="d-flex justify-content-end mb-2 gap-2">
                    <!-- Filter Button -->
                    <button class="btn btn-primary rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                        <i class="bx bx-filter"></i>
                    </button>
                </div>

                <!-- Collapsible Filter Section -->
                <div class="collapse" id="filterSection">
                    <div class="card card-body">
                        <div class="row row-cols-auto g-2">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="campusDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="campusDropdownLabel">Kampus</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="campusDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($campusFilter as $campus)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="campus[]" value="{{ $campus }}"
                                                    class="form-check-input me-2 campus-checkbox"
                                                    id="campus-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($campus, request('campus', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="campus-{{ $loop->index }}">{{ $campus }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="monthDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="monthDropdownLabel">Bulan</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="monthDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="month[]" value="{{ $m }}"
                                                    class="form-check-input me-2 month-checkbox"
                                                    id="month-{{ $m }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($m, request('month', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="month-{{ $m }}">
                                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                </label>
                                            </div>
                                            </li>
                                            @endfor
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="yearDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="yearDropdownLabel">Tahun</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="yearDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @for ($y = 2020; $y <= now()->year; $y++)
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" name="year[]" value="{{ $y }}"
                                                        class="form-check-input me-2 year-checkbox"
                                                        id="year-{{ $y }}"
                                                        style="transform: scale(1.3); margin-right: 8px;"
                                                        {{ in_array($y, request('year', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100"
                                                        for="year-{{ $y }}">
                                                        {{ \Carbon\Carbon::create()->year($y)->format('Y') }}
                                                    </label>
                                                </div>
                                            </li>
                                            @endfor
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="complainentCategoryDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="complainentCategoryDropdownLabel">Kategori Pengadu</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="complainentCategoryDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($complainentCategoryFilter as $complainentCategory)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="complainent_category[]"
                                                    value="{{ $complainentCategory }}"
                                                    class="form-check-input me-2 complainentCategory-checkbox"
                                                    id="complainentCategory-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($complainentCategory, request('complainent_category', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="complainentCategory-{{ $loop->index }}">{{ $complainentCategory }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="staffDutyDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="staffDutyDropdownLabel" class="text-truncate">PIC</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>

                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="staffDutyDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($staffDutyFilter as $staffDuty)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="staff_duty[]"
                                                    value="{{ $staffDuty }}"
                                                    class="form-check-input me-2 staffDuty-checkbox"
                                                    id="staffDuty-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($staffDuty, request('staff_duty', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="staffDuty-{{ $loop->index }}">{{ $staffDuty }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="categoryDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="categoryDropdownLabel">Kategori</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="categoryDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($categoryFilter as $category)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="category[]"
                                                    value="{{ $category }}"
                                                    class="form-check-input me-2 category-checkbox"
                                                    id="category-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($category, request('category', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="category-{{ $loop->index }}">{{ $category }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="aduanCategoryDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="aduanCategoryDropdownLabel">Aduan ICT</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="aduanCategoryDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($aduanCategoryFilter as $aduanCategory)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="aduan_category[]"
                                                    value="{{ $aduanCategory }}"
                                                    class="form-check-input me-2 aduanCategory-checkbox"
                                                    id="aduanCategory-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($aduanCategory, request('aduan_category', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="aduanCategory-{{ $loop->index }}">{{ $aduanCategory }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="aduanStatusDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="aduanStatusDropdownLabel">Aduan Status</span>
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-3" aria-labelledby="aduanStatusDropdown"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($aduanStatusFilter as $aduanStatus)
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" name="aduan_status[]"
                                                    value="{{ $aduanStatus }}"
                                                    class="form-check-input me-2 aduanStatus-checkbox"
                                                    id="aduanStatus-{{ $loop->index }}"
                                                    style="transform: scale(1.3); margin-right: 8px;"
                                                    {{ in_array($aduanStatus, request('aduan_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100"
                                                    for="aduanStatus-{{ $loop->index }}">{{ $aduanStatus }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Reset Button aligned to the right -->
                            <div class="col-lg-12 text-end">
                                <button type="button" class="btn btn-secondary rounded" id="resetButton">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mb-4">
        <h5 class="fw-bold mb-1">ANALISA ADUAN & PERKHIDMATAN - OPERASI BAHAGIAN INFOSTRUKTUR UiTM CAWANGAN SARAWAK</h5>
        <hr class="w-50 mx-auto border-primary">
    </div>

    <div class="row row-cols-lg-6 row-cols-md-3 row-cols-sm-1 g-3 mb-4">
        <!-- Main Statistic Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="bg-primary text-white p-4 rounded shadow-lg text-center d-flex flex-column justify-content-center h-100">
                <h5 class="fw-bold">JUMLAH ADUAN ICT</h5>
                <h2 class="fw-bold">{{ $totalAduan }}</h2>
            </div>
        </div>

        <!-- Status Cards -->
        @php
        $cards = [
        ['label' => 'COMPLETED', 'value' => $aduanCompleted, 'icon' => 'check-circle', 'percent' => $percentAduanCompleted, 'color' => 'success'],
        ['label' => 'IN PROGRESS', 'value' => $inProgress, 'icon' => 'spinner', 'percent' => $percentInProgress, 'color' => 'warning'],
        ['label' => 'CANCELLED', 'value' => $cancelled, 'icon' => 'times-circle', 'percent' => $percentCancelled, 'color' => 'danger'],
        ['label' => 'CLOSED', 'value' => $closed, 'icon' => 'lock', 'percent' => $percentClosed, 'color' => 'secondary'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col">
            <div class="card shadow-lg border-start border-4 border-{{ $card['color'] }} rounded-3 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center p-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fa fa-{{ $card['icon'] }} fa-lg text-{{ $card['color'] }} me-2"></i>
                        <h6 class="fw-bold mb-0">{{ $card['label'] }}</h6>
                    </div>
                    <h2 class="fw-bold my-2">{{ $card['value'] }}</h2>
                    <div class="progress w-75 mx-auto" style="height: 6px; border-radius: 5px;">
                        <div class="progress-bar bg-{{ $card['color'] }}" style="width: {{ $card['percent'] }}%;"></div>
                    </div>
                    <h6 class="text-muted mt-2">{{ $card['percent'] }}%</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Complainent Cards -->
    <div class="row justify-content-center">
        @php
        $complainents = [
        ['label' => 'STAFF', 'value' => $staff],
        ['label' => 'STUDENT', 'value' => $student],
        ['label' => 'GUEST', 'value' => $guest],
        ];

        $responseDays = [
        ['label' => '≤ 3 DAYS', 'value' => $responseDaysLessThanOrEqual3],
        ['label' => '> 3 DAYS', 'value' => $responseDaysMoreThan3],
        ];
        @endphp

        <!-- Campus Doughnut Chart -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    KAMPUS
                </div>
                <div class="card-body text-center">
                    <canvas id="campusChart" style="max-height: 305px; max-width: 750px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Complainant Doughnut Chart -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    PENGADU
                </div>
                <div class="card-body text-center">
                    <canvas id="complainentChart" style="max-height: 305px; max-width: 750px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Response Days Doughnut Chart -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    MASA TINDAK BALAS SELESAI (HARI)
                </div>
                <div class="card-body text-center">
                    <canvas id="responseDaysChart" style="max-height: 305px; max-width: 750px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Aduan -->
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    TOP 10 KATEGORI ADUAN
                </div>
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
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    SENARAI KATEGORI DAN SUBKATEGORI ADUAN
                </div>
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
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td style="word-wrap: break-word; white-space: normal;">
                                            {{ $categoryData['category'] }}
                                        </td>
                                        <td style="word-wrap: break-word; white-space: normal;">
                                            {{ $categoryData['subcategory'] }}
                                        </td>
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
            <div class="card">
                <div class="card-header text-center text-white text-uppercase h6" style="background-color: #03244c;">
                    Jumlah Aduan Mengikut Kategori Pengadu & Respons (Hari)
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <canvas id="complainantChart" width="500" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    JUMLAH ADUAN BULANAN MENGIKUT KATEGORI ADUAN
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="row justify-content-center flex-grow-1">
                        <canvas id="aduanMonthCatChart" width="500" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 1ST LEVEL STAFF -->
    <div class="row">
        <!-- Table Column -->
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                    JUMLAH ADUAN MENGIKUT 1ST LEVEL STAFF
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table id="aduan1stLevelTable" class="table table-sm table-striped table-hover"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Staf</th>
                                    <th>
                                        ≤ 3 Hari</th>
                                    <th>> 3 Hari</th>
                                    <th>Jumlah Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $grandTotalLessThan3Days = 0;
                                $grandTotalMoreThan3Days = 0;
                                $grandTotal = 0;
                                @endphp

                                @if (count($aduanBySelectedStaff) > 0)
                                @foreach ($aduanBySelectedStaff as $staff => $aduanCounts)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $staff }}</td>
                                    <td>{{ $aduanCounts['lessThan3Days'] }}</td>
                                    <td>{{ $aduanCounts['moreThan3Days'] }}</td>
                                    <td>{{ $aduanCounts['total'] }}</td>
                                </tr>

                                @php
                                // Accumulate totals for grand total calculation
                                $grandTotalLessThan3Days += $aduanCounts['lessThan3Days'];
                                $grandTotalMoreThan3Days += $aduanCounts['moreThan3Days'];
                                $grandTotal += $aduanCounts['total'];
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">Tiada rekod</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Jumlah Keseluruhan</strong></td>
                                    <td><strong>{{ $grandTotalLessThan3Days }}</strong></td>
                                    <td><strong>{{ $grandTotalMoreThan3Days }}</strong></td>
                                    <td><strong>{{ $grandTotal }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12">
            <!-- Total 1st & 2nd Level Card -->
            <div class="bg-info text-white p-4 card shadow mb-3 border-0 rounded">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="fw-bold text-dark text-center text-uppercase">JUMLAH 1ST & 2ND LEVEL</h5>
                    <h1 class="display-5 text-center">{{ $total1st2ndLevel }}</h1>
                </div>
            </div>

            <!-- 1st Level Card -->
            <div class="card shadow mb-3 border-0 rounded">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="fw-bold text-primary text-center text-uppercase">1ST Level</h5>
                    <h1 class="display-5 text-center">{{ $aduan1stLevel }}</h1>

                    <!-- Progress Bar for 1st Level -->
                    <div class="w-100 mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent1stLevel }}%;" aria-valuenow="{{ $percent1stLevel }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <h5 class="text-muted text-center mt-3">{{ $percent1stLevel }}%</h5>
                </div>
            </div>

            <!-- 2nd Level Card -->
            <div class="card shadow mb-3 border-0 rounded">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <h5 class="fw-bold text-primary text-center text-uppercase">2ND Level</h5>
                    <h1 class="display-5 text-center">{{ $aduan2ndLevel }}</h1>

                    <!-- Progress Bar for 2nd Level -->
                    <div class="w-100 mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent2ndLevel }}%;" aria-valuenow="{{ $percent2ndLevel }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <h5 class="text-muted text-center mt-3">{{ $percent2ndLevel }}%</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header text-center text-white h6" style="background-color: #03244c;">
                SENARAI ADUAN
            </div>
            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table id="aduanTable" class="table table-sm table-striped table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Aduan</th>
                                <th>Kategori</th>
                                <th>Sub Kategori</th>
                                <th>Pengadu</th>
                                <th>Kampus</th>
                                <th>Tempoh (Hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($aduanList) > 0)
                            @foreach ($aduanList as $index => $aduan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $aduan->aduan_ict_ticket }}</td>
                                <td>{{ $aduan->category }}</td>
                                <td>{{ $aduan->aduan_subcategory }}</td>
                                <td>{{ $aduan->complainent_category }}</td>
                                <td>{{ $aduan->campus }}</td>
                                <td>{{ $aduan->response_days }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7">Tiada rekod</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let timeout;

        // Detect changes in checkboxes and submit the form
        document.querySelectorAll(
            ".campus-checkbox, .month-checkbox, .year-checkbox, .complainentCategory-checkbox, .staffDuty-checkbox, .category-checkbox, .aduanCategory-checkbox, .aduanStatus-checkbox"
        ).forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    document.getElementById("searchForm").submit();
                }, 1000);
            });
        });

        // Reset button to clear selections
        document.getElementById("resetButton").addEventListener("click", function() {
            document.querySelectorAll(
                ".campus-checkbox,.month-checkbox, .year-checkbox, .complainentCategory-checkbox, .staffDuty-checkbox, .category-checkbox, .aduanCategory-checkbox, .aduanStatus-checkbox"
            ).forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById("searchForm").submit();
        });

        function updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText) {
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
            const selectedValues = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedValues.push(checkbox.nextElementSibling.innerText.trim());
                }
            });

            const label = document.getElementById(labelId);
            if (selectedValues.length > 0) {
                // If more than one item is selected, show the format "Pilih PIC (2)"
                label.textContent = `${defaultText} (${selectedValues.length})`;
            } else {
                label.textContent = defaultText;
            }
        }

        function attachListeners(dropdownId, labelId, checkboxClass, defaultText) {
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText);
                });
            });

            // Initial update on page load
            updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText);
        }

        // Apply to each filter
        attachListeners("campusDropdown", "campusDropdownLabel", "campus-checkbox", "Kampus");
        attachListeners("monthDropdown", "monthDropdownLabel", "month-checkbox", "Bulan");
        attachListeners("yearDropdown", "yearDropdownLabel", "year-checkbox", "Tahun");
        attachListeners("complainentCategoryDropdown", "complainentCategoryDropdownLabel",
            "complainentCategory-checkbox", "Kategori Pengadu");
        attachListeners("staffDutyDropdown", "staffDutyDropdownLabel", "staffDuty-checkbox", "PIC");
        attachListeners("categoryDropdown", "categoryDropdownLabel", "category-checkbox", "Kategori");
        attachListeners("aduanCategoryDropdown", "aduanCategoryDropdownLabel", "aduanCategory-checkbox",
            "Aduan ICT");
        attachListeners("aduanStatusDropdown", "aduanStatusDropdownLabel", "aduanStatus-checkbox",
            "Status Aduan");
    });
</script>


<!-- Campus -->
<script>
    // Use the passed data from PHP
    const campuses = <?php echo json_encode([
                            ['label' => 'SAMARAHAN', 'value' => (int) str_replace(',', '', $samarahan)],
                            ['label' => 'SAMARAHAN 2', 'value' => (int) str_replace(',', '', $samarahan2)],
                            ['label' => 'MUKAH', 'value' => (int) str_replace(',', '', $mukah)]
                        ]); ?>;


    // Check if campuses data is null or empty, set default values if so
    const safeCampuses = campuses && campuses.length > 0 ? campuses : [{
        label: 'No Data',
        value: 0
    }];

    // Dynamically adjust the content visibility based on data availability
    const campusCard = document.getElementById('campusCard');
    const campusChart = document.getElementById('campusChart');

    if (safeCampuses.length === 1 && safeCampuses[0].value === 0) {
        // When there's no data, show "No Data Available" and hide the chart
        campusChart.style.display = 'none'; // Hide the canvas
    } else {
        // Display the chart when there is data and hide the "No Data" message
        campusChart.style.display = 'block'; // Show the chart
    }

    // Prepare labels and data for the chart
    const kampusLabels = safeCampuses.map(item => item.label);
    const kampusData = safeCampuses.map(item => item.value);

    // Doughnut Chart Configuration
    const kampusCtx = document.getElementById('campusChart').getContext('2d');
    new Chart(kampusCtx, {
        type: 'doughnut',
        data: {
            labels: kampusLabels,
            datasets: [{
                label: 'Kampus Statistik',
                data: kampusData,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107'
                ],
                hoverBackgroundColor: [
                    '#0056b3', '#1e7e34', '#d39e00'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Disable maintaining aspect ratio
            plugins: {
                legend: {
                    position: 'bottom',
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
                        return `${value} \n (${percentage}%)`; // Display both total and percentage
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

<!-- Complainent Category -->
<script>
    const complainentData = <?php echo json_encode($complainents); ?>;

    const complainentLabels = complainentData.map(item => item.label);
    const complainentValues = complainentData.map(item => parseFloat(item.value.replace(/[^0-9.-]+/g, "")));

    // Doughnut Chart Configuration
    const complainentCtx = document.getElementById('complainentChart').getContext('2d');
    new Chart(complainentCtx, {
        type: 'doughnut',
        data: {
            labels: complainentLabels,
            datasets: [{
                label: 'Pengadu Statistik',
                data: complainentValues,
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                hoverBackgroundColor: ['#0056b3', '#1e7e34', '#d39e00']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
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
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${value} \n (${percentage}%)`; // Display both total and percentage
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

<!-- Response Days -->
<script>
    const responseDaysData = <?php echo json_encode($responseDays); ?>;

    const responseDaysLabels = responseDaysData.map(item => item.label);
    const responseDaysValues = responseDaysData.map(item => parseFloat(item.value.replace(/[^0-9.-]+/g, ""))); // Convert to number

    // Doughnut Chart Configuration
    const responseDaysCtx = document.getElementById('responseDaysChart').getContext('2d');
    new Chart(responseDaysCtx, {
        type: 'doughnut',
        data: {
            labels: responseDaysLabels,
            datasets: [{
                label: 'Masa Tindak Balas Statistik',
                data: responseDaysValues,
                backgroundColor: ['#007bff', '#ffc107'],
                hoverBackgroundColor: ['#0056b3', '#d39e00']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
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
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${value} \n (${percentage}%)`; // Display both total and percentage
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
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
                        display: false,
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
                            const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum +
                                val, 0);
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
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            const day = tooltipItems[0].label;
                            const total = totalComplaints[day];
                            return `${day} Hari\nJumlah Aduan: ${total}`;
                        },
                    },
                },
                datalabels: {
                    display: true,
                    color: 'black',
                    align: 'end', // Align label at the top of the bar
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
                    offset: 1,
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
            layout: {
                padding: {
                    top: 40, // Add padding at the top to avoid overlap with the legend
                    left: 10, // Space from the left
                    right: 10, // Space from the right
                    bottom: 10 // Space from the bottom
                }
            }
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

    // Calculate totals for each month
    const monthlyTotals = aduanMonthCategoryChart.map(data => {
        return categories.reduce((total, category) => total + (data[category] || 0), 0);
    });

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
                    callbacks: {
                        // Add total value at the top of the tooltip
                        afterTitle: (tooltipItems) => {
                            const total = tooltipItems.reduce((sum, item) => sum + item.raw, 0);
                            return `Jumlah Aduan: ${total}`;
                        },
                    },
                },
                legend: {
                    position: 'bottom', // Place the legend at the bottom
                },
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Bulan',
                    },
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Aduan',
                    },
                },
            },
            layout: {
                padding: {
                    top: 40, // Add padding at the top to avoid overlap with the legend
                    left: 10, // Space from the left
                    right: 10, // Space from the right
                    bottom: 10 // Space from the bottom
                }
            }
        },
        plugins: [{
            // Plugin to display totals and percentages at the end of each bar
            id: 'customTotalLabels',
            afterDatasetsDraw(chart) {
                const {
                    ctx,
                    chartArea,
                    data
                } = chart;
                const {
                    top
                } = chartArea;
                ctx.save();
                ctx.font = 'bold 12px Arial';
                ctx.textAlign = 'center';
                ctx.fillStyle = '#000';

                monthlyTotals.forEach((total, i) => {
                    const barCenter = chart.scales.x.getPixelForTick(i);
                    const barEnd = chart.scales.y.getPixelForValue(total);

                    // Calculate the percentage
                    const grandTotal = monthlyTotals.reduce((sum, t) => sum + t, 0);
                    const percentage = ((total / grandTotal) * 100).toFixed(1);

                    // Display total and percentage
                    ctx.fillText(`${total}`, barCenter, barEnd - 10); // First line: total
                    ctx.fillText(`(${percentage}%)`, barCenter, barEnd +
                        5); // Second line: percentage
                });

                ctx.restore();
            },
        }],
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

<script>
    $(document).ready(function() {
        var table = $('#aduanTable').DataTable({
            scrollY: "400px",
            scrollCollapse: true,
            paging: true,
            autoWidth: false,
            responsive: true,
            fixedHeader: true, // Add this line for fixed headers
            language: {
                paginate: {
                    previous: "Prev",
                    next: "Next",
                },
                search: "Cari:",
                lengthMenu: "Papar _MENU_ rekod setiap halaman",
                info: "Paparan _START_ hingga _END_ daripada _TOTAL_ rekod",
                infoEmpty: "Tiada rekod tersedia",
                zeroRecords: "Tiada padanan rekod ditemukan",
            },
        });

        table.columns.adjust();
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#aduan1stLevelTable').DataTable({
            paging: false,
            autoWidth: false,
            responsive: true,
            searching: false,
            fixedHeader: true,
            info: false, // Disable info (record count)
            language: {
                paginate: {
                    previous: "Prev",
                    next: "Next",
                },
                search: "Cari:",
                lengthMenu: "Papar _MENU_ rekod setiap halaman",
                zeroRecords: "Tiada padanan rekod ditemukan",
            },
        });

        table.columns.adjust();
    });
</script>

@endsection