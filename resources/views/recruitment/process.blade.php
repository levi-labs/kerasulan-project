@extends('layouts.master')
@section('content')
    <div class="row mb-2">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>


                    @if (session()->has('failed'))
                        <div class="alert alert-danger text-danger font-weight-bold" role="alert">
                            {{ session('failed') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success text-success font-weight-bold" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('data-training.import') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between">
                            <div class="col-md-2">
                                {{-- <a href="#predicted" class="btn btn-dark btn-sm float-left">Prediksi Nilai Baru</a> --}}
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-responsive mt-4">
                        <thead>
                            <tr>

                                <th> No </th>
                                <th> Nama </th>
                                <th> X1 </th>
                                <th> X2 </th>
                                <th> X3</th>
                                <th> net <br> choir </th>
                                <th> net <br> multimedia</th>
                                <th> net <br> soundman </th>
                                <th> output<br> choir</th>
                                <th> output<br> multimedia </th>
                                <th> output <br>soundman </th>
                                <th> target<br> choir</th>
                                <th> target <br>multimedia </th>
                                <th> target <br>soundman </th>
                                <th> error<br> choir </th>
                                <th> error <br>multimedia </th>
                                <th> error<br> soundman </th>
                                <th> w1<br>baru<br>choir </th>
                                <th> w2<br>baru<br>choir </th>
                                <th> w3<br>baru<br>choir </th>
                                <th> w1<br>baru<br>multimedia </th>
                                <th> w2<br>baru<br>multimedia </th>
                                <th> w3<br>baru<br>multimedia </th>
                                <th> w1<br>baru<br>soundman </th>
                                <th> w2<br>baru<br>soundman </th>
                                <th> w3<br>baru<br>soundman </th>
                                <th> bias<br>choir </th>
                                <th> bias<br>multimedia </th>
                                <th> bias<br>soundman </th>
                            </tr>
                        </thead>
                        <tbody>
                            <style>
                                .bg-green-light {
                                    background-color: #b3fcbd;
                                }

                                .bg-danger-light {
                                    background-color: #fcb3b3;
                                }
                            </style>
                            @foreach ($results as $key => $result)
                                <tr class="{{ $key % 2 == 0 ? 'bg-light' : 'bg-gray' }}">

                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $result['nama'] }} </td>
                                    <td> {{ $result['x1'] }} </td>
                                    <td> {{ $result['x2'] }} </td>
                                    <td> {{ $result['x3'] }} </td>
                                    <td> {{ $result['net_choir'] }} </td>
                                    <td>{{ $result['net_multimedia'] }}</td>
                                    <td>{{ $result['net_soundman'] }}</td>
                                    @if ($result['output_choir'] == $result['target_choir'])
                                        <td class="bg-green-light">{{ $result['output_choir'] }}</td>
                                    @else
                                        <td>{{ $result['output_choir'] }}</td>
                                    @endif

                                    @if ($result['output_multimedia'] == $result['target_multimedia'])
                                        <td class="bg-green-light">{{ $result['output_multimedia'] }}</td>
                                    @else
                                        <td>{{ $result['output_multimedia'] }}</td>
                                    @endif
                                    @if ($result['output_soundman'] == $result['target_soundman'])
                                        <td class="bg-green-light">{{ $result['output_soundman'] }}</td>
                                    @else
                                        <td>{{ $result['output_soundman'] }}</td>
                                    @endif
                                    @if ($result['target_choir'] == $result['output_choir'])
                                        <td class="bg-green-light">{{ $result['target_choir'] }}</td>
                                    @else
                                        <td>{{ $result['target_choir'] }}</td>
                                    @endif
                                    @if ($result['target_multimedia'] == $result['output_multimedia'])
                                        <td class="bg-green-light">{{ $result['target_multimedia'] }}</td>
                                    @else
                                        <td>{{ $result['target_multimedia'] }}</td>
                                    @endif
                                    @if ($result['target_soundman'] == $result['output_soundman'])
                                        <td class="bg-green-light">{{ $result['target_soundman'] }}</td>
                                    @else
                                        <td>{{ $result['target_soundman'] }}</td>
                                    @endif
                                    @if ($result['error_choir'] != 0)
                                        <td class="bg-danger-light">{{ $result['error_choir'] }}</td>
                                    @else
                                        <td>{{ $result['error_choir'] }}</td>
                                    @endif
                                    @if ($result['error_multimedia'] != 0)
                                        <td class="bg-danger-light">{{ $result['error_multimedia'] }}</td>
                                    @else
                                        <td>{{ $result['error_multimedia'] }}</td>
                                    @endif


                                    @if ($result['error_soundman'] != 0)
                                        <td class="bg-danger-light">{{ $result['error_soundman'] }}</td>
                                    @else
                                        <td>{{ $result['error_soundman'] }}</td>
                                    @endif
                                    <td>{{ $result['w1_choir'] }}</td>
                                    <td>{{ $result['w2_choir'] }}</td>
                                    <td>{{ $result['w3_choir'] }}</td>
                                    <td>{{ $result['w1_multimedia'] }}</td>
                                    <td>{{ $result['w2_multimedia'] }}</td>
                                    <td>{{ $result['w3_multimedia'] }}</td>
                                    <td>{{ $result['w1_soundman'] }}</td>
                                    <td>{{ $result['w2_soundman'] }}</td>
                                    <td>{{ $result['w3_soundman'] }}</td>
                                    <td>{{ $result['bias_choir'] }}</td>
                                    <td>{{ $result['bias_multimedia'] }}</td>
                                    <td>{{ $result['bias_soundman'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row mb-2">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Confusion Matrix</h4>
                    <table class="table table-bordered text-center">
                        <thead>
                            <th></th>
                            <th>Positives</th>
                            <th>Negatives</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Positives</td>
                                <td>
                                    True Positives : {{ $matrix['truePositives'] }}
                                </td>
                                <td>
                                    False Positives : {{ $matrix['falsePositives'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Negatives</td>
                                <td>
                                    False Negatives : {{ $matrix['falseNegatives'] }}
                                </td>
                                <td>
                                    True Negatives : {{ $matrix['trueNegatives'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Results</h4>
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <div class="aligner-wrapper">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="myChart" height="546" width="782" class="chartjs-render-monitor"
                                    style="display: block; height: 273px; width: 391px;"></canvas>
                                <div class="wrapper d-flex flex-column justify-content-center absolute absolute-center">
                                    <h3 class="text-center mb-0 font-weight-bold text-sm">Results</h3>
                                    <small class="d-block text-center text-muted  font-weight-semibold mb-0">Total
                                    </small>
                                </div>
                            </div>
                        </div>
                        <style>
                            .bg-code {
                                background-color: #fafafa;
                            }
                        </style>
                        <div class="col-md-8 p-4 bg-code">
                            <div class="row justify-content-center text-center">
                                <div class="col-md-4">
                                    <div class="font-weight-bold">
                                        <h3>Accuracy</h3>
                                        <h6 class="my-4">Accuracy = TP + TN / (TP+TN+FP+FN)</h6>
                                        <hr class="mb-4">
                                        <h5 class="my-4">Accuracy =
                                            {{ $matrix['truePositives'] . ' + ' . $matrix['trueNegatives'] }} /
                                            (
                                            {{ $matrix['truePositives'] . ' + ' . $matrix['trueNegatives'] . ' + ' . $matrix['falsePositives'] . ' + ' . $matrix['falseNegatives'] }}
                                            )
                                        </h5>
                                        <h5 class="my-5">
                                            Accuracy = {{ $matrix['truePositives'] + $matrix['trueNegatives'] }} /
                                            {{ $matrix['truePositives'] + $matrix['trueNegatives'] + $matrix['falsePositives'] + $matrix['falseNegatives'] }}
                                            = {{ $akurate }}%
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="font-weight-bold">
                                        <h3>Precision</h3>
                                        <h5 class="my-4">Precision = TP / (TP + FP)</h5>
                                        <hr class="mb-4">
                                        <h5 class="my-4">Precision =
                                            {{ $matrix['truePositives'] }} /
                                            (
                                            {{ $matrix['truePositives'] . ' + ' . $matrix['falsePositives'] }}
                                            )
                                        </h5>
                                        <h5 class="my-5">
                                            Precision = {{ $matrix['truePositives'] }} /
                                            {{ $matrix['truePositives'] + $matrix['falsePositives'] }}
                                            = {{ $precision }}%
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="font-weight-bold">
                                        <h3>Recall</h3>
                                        <h5 class="my-4">Recall = TP / (TP + FN)</h5>
                                        <hr class="mb-4">
                                        <h5 class="my-4">Recall =
                                            {{ $matrix['truePositives'] }} /
                                            (
                                            {{ $matrix['truePositives'] . ' + ' . $matrix['falseNegatives'] }}
                                            )
                                        </h5>
                                        <h5 class="my-5">
                                            Recall = {{ $matrix['truePositives'] }} /
                                            {{ $matrix['truePositives'] + $matrix['falseNegatives'] }}
                                            = {{ $recall }}%
                                        </h5>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');
        const data = {
            labels: [
                {!! json_encode($akurate) !!} + '%' + ' Accuracy', {!! json_encode($precision) !!} + '%' + ' Precision',
                {!! json_encode($recall) !!} + '%' + ' Recall'
            ],
            datasets: [{
                // label: 'My First Dataset',
                data: [{!! json_decode($akurate) !!}, {!! json_decode($precision) !!}, {!! json_decode($recall) !!}],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        new Chart(ctx, {
            type: 'doughnut',
            data: data,

        });
    </script> --}}
@endsection
