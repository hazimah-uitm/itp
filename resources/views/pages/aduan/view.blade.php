@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Aduan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aduan') }}">Senarai Aduan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Maklumat {{ $aduan->aduan_ict_ticket }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat {{ $aduan->aduan_ict_ticket }}</h6>
    <hr />

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Tiket Aduan ICT</th>
                            <td>{{ $aduan->aduan_ict_ticket }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pengadu</th>
                            <td>{{ $aduan->complainent_name }} ({{ $aduan->complainent_id }})</td>
                        </tr>
                        <tr>
                            <th>Kategori Pengadu</th>
                            <td>{{ $aduan->complainent_category }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $aduan->category }}</td>
                        </tr>
                        <tr>
                            <th>Kategori Aduan</th>
                            <td>{{ $aduan->aduan_category }}</td>
                        </tr>
                        <tr>
                            <th>Subkategori Aduan</th>
                            <td>{{ $aduan->aduan_subcategory }}</td>
                        </tr>
                        <tr>
                            <th>Kampus</th>
                            <td>{{ $aduan->campus }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $aduan->location }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan Aduan</th>
                            <td>{!! nl2br(e($aduan->aduan_details)) !!}</td>
                        </tr>
                        <tr>
                            <th>Jenis Aduan</th>
                            <td>{{ $aduan->aduan_type }}</td>
                        </tr>
                        <tr>
                            <th>Staf Bertugas</th>
                            <td>{{ $aduan->staff_duty }}</td>
                        </tr>
                        <tr>
                            <th>Catatan kepada Staf Bertugas</th>
                            <td>{!! nl2br(e($remark_staff_duty ?? '-')) !!}</td>
                        </tr>
                        <tr>
                            <th>Tarikh Aduan</th>
                            <td>{{ \Carbon\Carbon::parse($aduan->date_applied)->format('F Y') }}
                                {{ \Carbon\Carbon::parse($aduan->time_applied)->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Tarikh Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($aduan->date_completed)->format('F Y') }}
                                {{ \Carbon\Carbon::parse($aduan->time_completed)->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Tempoh Respons</th>
                            <td>{{ $aduan->response_time }}</td>
                        </tr>
                        <tr>
                            <th>Tempoh Respons Hari</th>
                            <td>{{ $aduan->response_days }}</td>
                        </tr>
                        <tr>
                            <th>Rating</th>
                            <td>
                                @if ($aduan->rating)
                                    {{ $aduan->rating }}<i class='bx bxs-star'></i>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
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
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Wrapper -->
@endsection
