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
    @role('Superadmin')
    <div class="ms-auto">
        <a href="{{ route('aduan.trash') }}">
            <button type="button" class="btn btn-primary mt-2 mt-lg-0">Senarai Rekod Dipadam</button>
        </a>
        <a href="{{ route('aduan.export', ['type' => request('type'), 'attendance' => request('attendance'), 'status' => request('status')]) }}"
            class="btn btn-success">
            Export to Excel
        </a>
    </div>
    @endrole
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
        <div class="row mb-4">
            <div class="col-lg-6">
                <form action="{{ route('aduan.search') }}" method="GET" id="searchForm"
                    class="d-lg-flex align-items-center gap-3">
                    <div class="input-group">
                        <input type="text" class="form-control rounded" placeholder="Carian..." name="search"
                            value="{{ request('search') }}" id="searchInput">

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

            <div class="col-lg-6 d-flex justify-content-end align-items-center gap-2">
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
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No. Pekerja</th>
                        <th>Kehadiran</th>
                        <th>Jenis Pengguna</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @if (count($aduanList) > 0)
                    @foreach ($aduanList as $aduan)
                    <tr>
                        <td>{{ ($aduanList->currentPage() - 1) * $aduanList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ ucfirst($aduan->name) }}</td>
                        <td>{{ $aduan->no_pekerja }}</td>
                        <td>{{ $aduan->attendance }}</td>
                        <td>{{ $aduan->type }}</td>
                        <td>
                            @if ($aduan->status == 'Belum Tempah')
                            <span class="badge bg-warning">Belum Tempah</span>
                            @else
                            <span class="badge bg-success">Selesai Tempah</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('aduan.edit', $aduan->id) }}" class="btn btn-info btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kemaskini">
                                <i class="bx bxs-edit"></i>
                            </a>
                            <a href="{{ route('aduan.show', $aduan->id) }}" class="btn btn-primary btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Papar">
                                <i class="bx bx-show"></i>
                            </a>

                            <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-title="Padam">
                                <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $aduan->id }}"><i
                                        class="bx bx-trash"></i></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else --}}
                    <td colspan="9">Tiada rekod</td>
                    {{-- @endif --}}
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                <form action="{{ route('aduan.search') }}" method="GET" id="perPageForm"
                    class="d-flex align-items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="attendance" value="{{ request('attendance') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
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
                                'perPage' => request('perPage'),
                                'type' => request('type'),
                                'status' => request('status'),
                                'attendance' => request('attendance'),
                            ])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@foreach ($aduanList as $aduan)
<div class="modal fade" id="deleteModal{{ $aduan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @isset($aduan)
                Adakah anda pasti ingin memadam rekod <span style="font-weight: 600;">
                    {{ ucfirst($aduan->name) }}</span>?
                @else
                Tiada maklumat Aduan.
                @endisset
            </div>
            <div class="modal-footer">
                <form action="{{ route('aduan.destroy', $aduan->id) }}" method="POST">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Padam</button>
                </form>
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

        document.getElementById('typeFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('attendanceFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('resetButton').addEventListener('click', function() {
            // Redirect to the base route to clear query parameters
            window.location.href = "{{ route('aduan') }}";
        });

    });
</script>
@endsection