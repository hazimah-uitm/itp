@extends('layouts.master')

@section('content')
<div class="container-fluid mb-3">
    <div class="row mb-4">
        <!-- Jumlah Aduan ICT (Main Card) -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary" style="font-size: 1.5rem;">
                <div class="card-header text-center">Jumlah Aduan ICT</div>
                <div class="card-body text-center">
                    <h1 class="card-title display-4">{{ $totalAduan }}</h1>
                </div>
            </div>
        </div>

        <!-- Aduan Completed Card -->
        <div class="col-md-2 mb-3">
            <div class="card text-white bg-success">
                <div class="card-header text-center">Aduan Completed</div>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $aduanCompleted }}</h5>
                    <p class="text-white">{{ $percentAduanCompleted }}%</p>
                </div>
            </div>
        </div>

        <!-- In Progress Card -->
        <div class="col-md-2 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-header text-center">In Progress</div>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $inProgress }}</h5>
                    <p class="text-white">{{ $percentInProgress }}%</p>
                </div>
            </div>
        </div>

        <!-- Cancelled Card -->
        <div class="col-md-2 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-header text-center">Cancelled</div>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $cancelled }}</h5>
                    <p class="text-white">{{ $percentCancelled }}%</p>
                </div>
            </div>
        </div>

        <!-- Closed Card -->
        <div class="col-md-2 mb-3">
            <div class="card text-white bg-secondary">
                <div class="card-header text-center">Closed</div>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $closed }}</h5>
                    <p class="text-white">{{ $percentClosed }}%</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
