@extends('layouts.master')
@section('content')
    {{-- <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sessions by channel</h4>
                    <div class="aligner-wrapper">
                        <canvas id="sessionsDoughnutChart" height="210"></canvas>
                        <div class="wrapper d-flex flex-column justify-content-center absolute absolute-center">
                            <h2 class="text-center mb-0 font-weight-bold">8.234</h2>
                            <small class="d-block text-center text-muted  font-weight-semibold mb-0">Total
                                Leads</small>
                        </div>
                    </div>
                    <div class="wrapper mt-4 d-flex flex-wrap align-items-cente">
                        <div class="d-flex">
                            <span class="square-indicator bg-danger ml-2"></span>
                            <p class="mb-0 ml-2">Assigned</p>
                        </div>
                        <div class="d-flex">
                            <span class="square-indicator bg-success ml-2"></span>
                            <p class="mb-0 ml-2">Not Assigned</p>
                        </div>
                        <div class="d-flex">
                            <span class="square-indicator bg-warning ml-2"></span>
                            <p class="mb-0 ml-2">Reassigned</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body performane-indicator-card">
                    <div class="d-sm-flex">
                        <h4 class="card-title flex-shrink-1">Performance Indicator</h4>
                        <p class="m-sm-0 ml-sm-auto flex-shrink-0">
                            <span class="data-time-range ml-0">7d</span>
                            <span class="data-time-range active">2w</span>
                            <span class="data-time-range">1m</span>
                            <span class="data-time-range">3m</span>
                            <span class="data-time-range">6m</span>
                        </p>
                    </div>
                    <div class="d-sm-flex flex-wrap">
                        <div class="d-flex align-items-center">
                            <span class="dot-indicator bg-primary ml-2"></span>
                            <p class="mb-0 ml-2 text-muted font-weight-semibold">Complaints (2098)</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="dot-indicator bg-info ml-2"></span>
                            <p class="mb-0 ml-2 text-muted font-weight-semibold"> Task Done (1123)</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="dot-indicator bg-danger ml-2"></span>
                            <p class="mb-0 ml-2 text-muted font-weight-semibold">Attends (876)</p>
                        </div>
                    </div>
                    <div id="performance-indicator-chart" class="ct-chart mt-4"></div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Quick Action Toolbar Ends-->
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session()->has('success'))
                                <div class="alert alert-success text-success font-weight-bold" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="d-sm-flex align-items-baseline report-summary-header">
                                <h5 class="font-weight-semibold">Dashboard</h5> <span class="ml-auto">Updated
                                    Data</span> <button class="btn btn-icons border-0 p-2"><i
                                        class="icon-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row report-inner-cards-wrapper">
                        <div class=" col-md-4 report-inner-card">
                            <div class="inner-card-text">
                                <span class="report-title">Data Training</span>
                                <h4>{{ $data_training }}</h4>
                                {{-- <span class="report-count"> 2 Reports</span> --}}
                            </div>
                            <div class="inner-card-icon bg-success">
                                <i class="icon-rocket"></i>
                            </div>
                        </div>
                        <div class="col-md-4 report-inner-card">
                            <div class="inner-card-text">
                                <span class="report-title">Data Recruitment</span>
                                <h4>{{ $recruitment }}</h4>
                                {{-- <span class="report-count"> 3 Reports</span> --}}
                            </div>
                            <div class="inner-card-icon bg-danger">
                                <i class="icon-briefcase"></i>
                            </div>
                        </div>
                        <div class="col-md-4 report-inner-card">
                            <div class="inner-card-text">
                                <span class="report-title">User</span>
                                <h4>{{ $user }}</h4>
                                {{-- <span class="report-count"> 5 Reports</span> --}}
                            </div>
                            <div class="inner-card-icon bg-warning">
                                <i class="icon-globe-alt"></i>
                            </div>
                        </div>
                        {{-- <div class="col-md-6 col-xl report-inner-card">
                            <div class="inner-card-text">
                                <span class="report-title">RETURN</span>
                                <h4>25,542</h4>
                                <span class="report-count"> 9 Reports</span>
                            </div>
                            <div class="inner-card-icon bg-primary">
                                <i class="icon-diamond"></i>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3>Grafik Data Training</h3>
                    <canvas id="myChart" style="width: 400px; height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_training->pluck('bidang')->toArray()) !!},
                datasets: [{
                    label: 'Predict',
                    data: {!! json_encode($chart_training->pluck('count')->toArray()) !!},
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
