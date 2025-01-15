@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Aduan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Aduan</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<h6 class="mb-0 text-uppercase">Senarai Aduan</h6>
<hr />
@if (session('success'))
<div class="alert alert-success mt-2">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger mt-2">
    {{ session('error') }}
</div>
@endif
<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-lg-12">
                <form action="{{ route('aduan.search') }}" method="GET" id="searchForm"
                    class="d-lg-flex align-items-center gap-3">
                    <div class="input-group">
                        <input type="text" class="form-control rounded" placeholder="Carian..." name="search"
                            value="{{ request('search') }}" id="searchInput">

                        <select name="campus" class="form-select form-select-sm ms-2 rounded" id="campusFilter">
                            <option value="all" {{ request('campus', 'all') == 'all' ? 'selected' : '' }}>Semua Kampus</option>
                            @foreach ($campusFilter as $campus)
                            <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>
                                {{ $campus }}
                            </option>
                            @endforeach
                        </select>

                        <select name="aduan_status" class="form-select form-select-sm ms-2 rounded" id="aduanStatusFilter">
                            <option value="all" {{ request('aduan_status', 'all') == 'all' ? 'selected' : '' }}>Semua Aduan Status</option>
                            @foreach ($aduanStatusFilter as $aduanStatus)
                            <option value="{{ $aduanStatus }}" {{ request('aduan_status') == $aduanStatus ? 'selected' : '' }}>
                                {{ $aduanStatus }}
                            </option>
                            @endforeach
                        </select>

                        <select name="month" class="form-select form-select-sm ms-2 rounded" id="monthFilter">
                            <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                                @endfor
                        </select>

                        <select name="year" class="form-select form-select-sm ms-2 rounded" id="yearFilter">
                            <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endfor
                        </select>

                        <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        <button type="submit" class="btn btn-primary ms-1 rounded" id="searchButton">
                            <i class="bx bx-search"></i>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1 rounded" id="resetButton">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12 d-flex justify-content-end align-items-center">
                <!-- Import Button and Form -->
                <form action="{{ route('aduan.import') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex align-items-center">
                    {{ csrf_field() }}
                    <div class="form-group mb-0">
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-info ms-2">Import</button>
                </form>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead class="table-light text-center text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Kampus</th>
                        <th>Status Aduan</th>
                        <th>Kategori</th>
                        <th>Kategori Aduan</th>
                        <th>Staf Bertugas</th>
                        <th>Bulan Aduan</th>
                        <th>Tahun Aduan</th>
                        <th>Tempoh Respons</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($aduanList) > 0)
                    @foreach ($aduanList as $aduan)
                    <tr>
                        <td>{{ ($aduanList->currentPage() - 1) * $aduanList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ $aduan->campus }}</td>
                        <td>
                            @if ($aduan->aduan_status == 'ADUAN CANCELLED')
                            <span class="badge bg-warning">ADUAN CANCELLED</span>
                            @elseif ($aduan->aduan_status == 'ADUAN CLOSED (INCOMPLETE INFORMATION / WRONG CHANNEL)')
                            <span class="badge bg-secondary">ADUAN CLOSED</span>
                            @elseif ($aduan->aduan_status == 'ADUAN COMPLETED')
                            <span class="badge bg-success">ADUAN COMPLETED</span>
                            @elseif ($aduan->aduan_status == 'ADUAN VERIFIED')
                            <span class="badge bg-primary">ADUAN VERIFIED</span>
                            @else
                            <span class="badge bg-info">IT SERVICES - 2ND LEVEL SUPPORT</span>
                            @endif
                        </td>
                        <td>{{ $aduan->category }}</td>
                        <td style="word-wrap: break-word; white-space: normal;">{{ $aduan->aduan_category }}</td>
                        <td style="word-wrap: break-word; white-space: normal;">{{ $aduan->staff_duty}}</td>
                        <td>{{ $aduan->month }}</td>
                        <td>{{ $aduan->year }}</td>
                        <td style="word-wrap: break-word; white-space: normal;">{{ $aduan->response_time }}</td>
                        <td>
                            <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-title="Papar">
                                <span class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#showModal{{ $aduan->id }}"><i
                                        class="bx bx-show"></i></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <td colspan="10">Tiada rekod</td>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                <form action="{{ route('aduan.search') }}" method="GET" id="perPageForm"
                    class="d-flex align-items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="campus" value="{{ request('campus') }}">
                    <input type="hidden" name="aduan_status" value="{{ request('aduan_status') }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    <select name="perPage" id="perPage" class="form-select form-select-sm"
                        onchange="document.getElementById('perPageForm').submit()">
                        <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                        <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                    </select>
                </form>
            </div>

            <div class="d-flex justify-content-end align-items-center">
                <span class="mx-2 mt-2 small text-muted">
                    Menunjukkan {{ $aduanList->firstItem() }} hingga {{ $aduanList->lastItem() }} daripada
                    {{ $aduanList->total() }} rekod
                </span>
                <div class="pagination-wrapper">
                    {{ $aduanList->appends([
                                'search' => request('search'),
                                'month' => request('month'),
                                'year' => request('year'),
                                'campus' => request('campus'),
                                'aduan_status' => request('aduan_status'),
                                'perPage' => request('perPage'),
                            ])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Show Modal -->
@foreach ($aduanList as $aduan)
<div class="modal fade" id="showModal{{ $aduan->id }}" tabindex="-1"
    aria-labelledby="showModalLabel{{ $aduan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="showModalLabel{{ $aduan->id }}">Maklumat:
                    {{ $aduan->aduan_ict_ticket }}
                </h5>
                <div class="d-flex align-items-center">
                    <a href="{{ route('aduan.show', $aduan->id) }}" class="btn btn-link me-0" style="padding: 0"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Paparan Penuh">
                        <i class='bx bx-fullscreen'></i>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tutup"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table class="table table-borderless table-striped table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Tiket Aduan ICT</th>
                            <td>{{ $aduan->aduan_ict_ticket }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nama Pengadu</th>
                            <td>{{ $aduan->complainent_name }} ({{ $aduan->complainent_id }})</td>
                        </tr>
                        <tr>
                            <th scope="row">Kategori Pengadu</th>
                            <td>{{ $aduan->complainent_category }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kategori</th>
                            <td>{{ $aduan->category }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kategori Aduan</th>
                            <td>{{ $aduan->aduan_category }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Subkategori Aduan</th>
                            <td>{{ $aduan->aduan_subcategory }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kampus</th>
                            <td>{{ $aduan->campus }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Lokasi</th>
                            <td>{{ $aduan->location }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Keterangan Aduan</th>
                            <td style="word-wrap: break-word; white-space: normal;">{!! nl2br(e($aduan->aduan_details)) !!}</td>
                        </tr>
                        <tr>
                            <th scope="row">Jenis Aduan</th>
                            <td>{{ $aduan->aduan_type }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Staf Bertugas</th>
                            <td>{{ $aduan->staff_duty }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Catatan kepada Staf Bertugas</th>
                            <td>{!! nl2br(e($remark_staff_duty ?? '-')) !!}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tarikh Aduan</th>
                            <td>{{ \Carbon\Carbon::parse($aduan->date_applied)->format('d F Y') }}
                                {{ \Carbon\Carbon::parse($aduan->time_applied)->format('h:i A') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tarikh Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($aduan->date_completed)->format('d F Y') }}
                                {{ \Carbon\Carbon::parse($aduan->time_completed)->format('h:i A') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tempoh Respons</th>
                            <td>{{ $aduan->response_time }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempoh Respons Hari</th>
                            <td>{{ $aduan->response_days }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Rating</th>
                            <td>
                                @if ($aduan->rating)
                                {{ $aduan->rating }} <i class='bx bxs-star text-warning'></i>
                                @else
                                <span class="text-muted">Belum Dinilai</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td>
                                <span
                                    class="badge 
                                        @if ($aduan->aduan_status == 'ADUAN CANCELLED') bg-warning
                                        @elseif ($aduan->aduan_status == 'ADUAN COMPLETED') bg-success
                                        @elseif ($aduan->aduan_status == 'ADUAN VERIFIED') bg-primary
                                        @else bg-info @endif">
                                    {{ $aduan->aduan_status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form on input change
        document.getElementById('searchInput').addEventListener('input', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('aduanStatusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

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
            window.location.href = "{{ route('aduan') }}";
        });

    });
</script>
@endsection