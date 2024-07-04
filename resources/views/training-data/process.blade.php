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
                                <a href="#predicted" class="btn btn-dark btn-sm float-left">Prediksi Nilai Baru</a>
                            </div>
                        </div>
                    </form>
                    <style>
                        .table.table-bordered.table-responsive tbody td {
                            font-size: 14px !important;
                        }
                    </style>

                    <table class="table table-bordered table-responsive mt-4">
                        <thead>
                            <tr>
                                <th> Epoch</th>
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
                            @foreach ($result as $key => $item)
                                <style>
                                    .bg-green-light {
                                        background-color: #b3fcbd;
                                    }

                                    .bg-danger-light {
                                        background-color: #fcb3b3;
                                    }
                                </style>

                                <td rowspan="16"
                                    class="text-center font-weight-bold {{ $key % 2 == 0 ? 'bg-light' : 'bg-gray' }}">
                                    {{ $key + 1 }}
                                    @foreach ($item as $res)
                                        <tr class="{{ $key % 2 == 0 ? 'bg-light' : 'bg-gray' }}">

                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $res['nama'] }} </td>
                                            <td> {{ $res['choir'] }} </td>
                                            <td> {{ $res['multimedia'] }} </td>
                                            <td> {{ $res['soundman'] }} </td>
                                            <td> {{ $res['net_choir'] }} </td>
                                            <td>{{ $res['net_multimedia'] }}</td>
                                            <td>{{ $res['net_soundman'] }}</td>
                                            @if ($res['output_choir'] == $res['target_choir'])
                                                <td class="bg-green-light">{{ $res['output_choir'] }}</td>
                                            @else
                                                <td>{{ $res['output_choir'] }}</td>
                                            @endif

                                            @if ($res['output_multimedia'] == $res['target_multimedia'])
                                                <td class="bg-green-light">{{ $res['output_multimedia'] }}</td>
                                            @else
                                                <td>{{ $res['output_multimedia'] }}</td>
                                            @endif
                                            @if ($res['output_soundman'] == $res['target_soundman'])
                                                <td class="bg-green-light">{{ $res['output_soundman'] }}</td>
                                            @else
                                                <td>{{ $res['output_soundman'] }}</td>
                                            @endif
                                            @if ($res['target_choir'] == $res['output_choir'])
                                                <td class="bg-green-light">{{ $res['target_choir'] }}</td>
                                            @else
                                                <td>{{ $res['target_choir'] }}</td>
                                            @endif
                                            @if ($res['target_multimedia'] == $res['output_multimedia'])
                                                <td class="bg-green-light">{{ $res['target_multimedia'] }}</td>
                                            @else
                                                <td>{{ $res['target_multimedia'] }}</td>
                                            @endif
                                            @if ($res['target_soundman'] == $res['output_soundman'])
                                                <td class="bg-green-light">{{ $res['target_soundman'] }}</td>
                                            @else
                                                <td>{{ $res['target_soundman'] }}</td>
                                            @endif
                                            @if ($res['error_choir'] != 0)
                                                <td class="bg-danger-light">{{ $res['error_choir'] }}</td>
                                            @else
                                                <td>{{ $res['error_choir'] }}</td>
                                            @endif
                                            @if ($res['error_multimedia'] != 0)
                                                <td class="bg-danger-light">{{ $res['error_multimedia'] }}</td>
                                            @else
                                                <td>{{ $res['error_multimedia'] }}</td>
                                            @endif

                                            @if ($res['error_soundman'] != 0)
                                                <td class="bg-danger-light">{{ $res['error_soundman'] }}</td>
                                            @else
                                                <td>{{ $res['error_soundman'] }}</td>
                                            @endif
                                            <td>{{ $res['w1_baru_choir'] }}</td>
                                            <td>{{ $res['w2_baru_choir'] }}</td>
                                            <td>{{ $res['w3_baru_choir'] }}</td>
                                            <td>{{ $res['w1_baru_multimedia'] }}</td>
                                            <td>{{ $res['w2_baru_multimedia'] }}</td>
                                            <td>{{ $res['w3_baru_multimedia'] }}</td>
                                            <td>{{ $res['w1_baru_soundman'] }}</td>
                                            <td>{{ $res['w2_baru_soundman'] }}</td>
                                            <td>{{ $res['w3_baru_soundman'] }}</td>
                                            <td>{{ $res['bias_baru_choir'] }}</td>
                                            <td>{{ $res['bias_baru_multimedia'] }}</td>
                                            <td>{{ $res['bias_baru_soundman'] }}</td>




                                            {{-- <td> {{ $result->x1 }} </td>
                                            <td> {{ $result['audio_video'] }} </td>
                                            <td> {{ $result['v'] }} </td> --}}
                                            {{-- @if ($result['y'] != $result['y_target'])
                                                <td class="bg-danger-light"> {{ $result['y'] }} </td>
                                                <td class="bg-danger-light"> {{ $result['y_target'] }} </td>
                                            @else
                                                <td class="bg-green-light"> {{ $result['y'] }} </td>
                                                <td class="bg-green-light"> {{ $result['y_target'] }} </td>
                                            @endif
                                            <td> {{ $result['error'] }} </td>
                                            <td> {{ $result['w1_baru'] }} </td>
                                            <td> {{ $result['w2_baru'] }} </td>
                                            <td> {{ $result['delta_w1'] }} </td>
                                            <td> {{ $result['delta_w2'] }} </td> --}}

                                        </tr>
                                    @endforeach
                                </td>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2" id="predicted">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title my-2 mx-2">
                        <h4>Prediksi Nilai Baru</h4>
                    </div>
                    @if (isset($predicted))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success text-success font-weight-bold" role="alert">
                                    {{ $message }}
                                </div>
                                <a href="{{ route('training-data.process') }}" class="btn btn-dark"> Prediksi Nilai
                                    Baru</a>
                            </div>
                        </div>
                    @else
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <form class="forms-sample" method="POST" action="{{ route('training-data.predicted') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nik">Nama</label>
                                        <input type="text" class="form-control" id="nik" name="nama"
                                            placeholder="Nama Recruiter">
                                        @error('nik')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mambaca Not Angka</label>
                                        <input type="number" class="form-control" id="" name="not_angka"
                                            placeholder="">
                                        @error('not_angka')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mengoperasikan Software</label>
                                        <input type="number" class="form-control" id="" name="software"
                                            placeholder="">
                                        @error('software')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mengoperasikan Audio</label>
                                        <input type="number" class="form-control" id="" name="audio"
                                            placeholder="">
                                        @error('audio')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-dark mr-2">Submit</button>
                                    <button class="btn btn-sm btn-light" type="button"
                                        onclick="window.location='{{ route('recruitments.index') }}'">Cancel</button>
                                </form>
                            </div>
                        </div>
                    @endif



                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title my-2 mx-2">
                        <h4>Hasil Prediksi</h4>
                        <div class="row justify-content-center ">
                            <style>
                                .bidang {
                                    width: 40% !important;
                                    border-radius: 100% !important;
                                    height: 45% !important;
                                }
                            </style>
                            <div class="col-md-6  text-center">
                                <style>
                                    .my-size {
                                        font-size: 60px !important;
                                    }

                                    .text-color {
                                        color: white !important;
                                    }

                                    .bg-gradient-one {
                                        background: linear-gradient(90deg, rgb(175, 112, 223) 0%, rgb(72, 28, 230) 35%, rgba(0, 212, 255, 1) 100%);
                                    }

                                    .bg-gradient-one:hover {
                                        background: linear-gradient(90deg, rgb(203, 147, 246) 0%, rgb(93, 54, 235) 35%, rgb(15, 193, 229) 100%) !important;
                                    }
                                </style>

                                @if (isset($predicted))

                                    @if ($predicted['output_choir'] == 1 && $predicted['output_multimedia'] == 0 && $predicted['output_soundman'] == 0)
                                        <button type="button" class="btn bg-gradient-one bidang mt-5 ">
                                            <i class="icon-music-tone my-size text-color"></i>
                                            <i class="icon-microphone my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        <h3 class="font-weight-bold">Choir</h3>
                                    @elseif($predicted['output_choir'] == 0 && $predicted['output_multimedia'] == 1 && $predicted['output_soundman'] == 0)
                                        <button type="button" class="btn bg-gradient-one bidang mt-5">
                                            <i class="icon-music-tone-alt my-size text-color"></i>&nbsp;
                                            <i class="icon-screen-desktop my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        <h3 class="font-weight-bold">Multimedia</h3>
                                    @elseif($predicted['output_choir'] == 0 && $predicted['output_multimedia'] == 0 && $predicted['output_soundman'] == 1)
                                        <button type="button" class="btn bg-gradient-one bidang mt-5">
                                            <i class="icon-music-tone-alt my-size text-color"></i>&nbsp;
                                            <i class="icon-microphone my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        <h3 class="font-weight-bold">Soundman</h3>
                                    @else
                                        <button type="button" class="btn bg-gradient-one bidang mt-5">
                                            <i class="icon-music-tone-alt my-size text-color"></i>&nbsp;
                                            <i class="icon-screen-desktop my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        @if ($predicted['output_choir'] == 1 && $predicted['output_multimedia'] == 1 && $predicted['output_soundman'] == 0)
                                            <h3 class="font-weight-bold">Choir / Multimedia</h3>
                                        @elseif ($predicted['output_choir'] == 1 && $predicted['output_multimedia'] == 0 && $predicted['output_soundman'] == 1)
                                            <h3 class="font-weight-bold">Choir / Soundman</h3>
                                        @elseif ($predicted['output_choir'] == 0 && $predicted['output_multimedia'] == 1 && $predicted['output_soundman'] == 1)
                                            <h3 class="font-weight-bold">Multimedia / Soundman</h3>
                                        @elseif ($predicted['output_choir'] == 1 && $predicted['output_multimedia'] == 1 && $predicted['output_soundman'] == 1)
                                            <h3 class="font-weight-bold">Choir / Multimedia / Soundman</h3>
                                        @else
                                            <h3 class="font-weight-bold">Tidak Terprediksi</h3>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if (isset($predicted))
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <td>Nama:</td>
                                            <td>{{ $predicted['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nilai Membaca Not Angka:
                                            </td>
                                            <td>{{ $predicted['x1'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai Software:</td>
                                            <td>{{ $predicted['x2'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai Audio:</td>
                                            <td>{{ $predicted['x3'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Net Choir:</td>
                                            <td>{{ $predicted['net_choir'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Net Multimedia:</td>
                                            <td>{{ $predicted['net_multimedia'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Net Soundman:</td>
                                            <td>{{ $predicted['net_soundman'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Output Choir:</td>
                                            <td>{{ $predicted['output_choir'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Output Multimedia:</td>
                                            @php
                                                if ($predicted['target_multimedia'] == 1) {
                                                    $target = 'Multimedia';
                                                    # code...
                                                } elseif ($predicted['target_choir'] == 1) {
                                                    $target = 'Choir';
                                                    # code...
                                                } elseif ($predicted['target_soundman'] == 1) {
                                                    $target = 'Soundman';
                                                }
                                            @endphp
                                            <td>{{ $predicted['output_multimedia'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Output Soundman:</td>
                                            <td>{{ $predicted['output_soundman'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Target Choir:</td>

                                            <td>{{ $predicted['target_choir'] }} </td>
                                        </tr>
                                        <tr>
                                            <td>Target Multimedia:</td>
                                            <td>{{ $predicted['target_multimedia'] }} </td>
                                        </tr>
                                        <tr>
                                            <td>Target Soundman:</td>
                                            <td>{{ $predicted['target_soundman'] }} </td>
                                        </tr>
                                        {{-- <tr>
                                            <td>Nilai luaran Y:</td>
                                            @if ($predicted['y_luaran'] != $predicted['y_target'])
                                                <td class="bg-danger-light"> {{ $predicted['y_luaran'] }} </td>
                                            @else
                                                <td class="bg-green-light"> {{ $predicted['y_luaran'] }} </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Nilai Target Y:</td>
                                            @if ($predicted['y_luaran'] != $predicted['y_target'])
                                                <td class="bg-danger-light"> {{ $predicted['y_target'] }} </td>
                                            @else
                                                <td class="bg-green-light"> {{ $predicted['y_target'] }} </td>
                                            @endif
                                        </tr> --}}
                                        {{-- <tr>
                                            <td>Nilai Error:</td>
                                            <td>
                                                {{ $predicted['error'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nilai W1:
                                            </td>
                                            <td>
                                                {{ $predicted['w1_baru'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nilai W2:
                                            </td>
                                            <td>
                                                {{ $predicted['w2_baru'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nilai Delta W1:
                                            </td>
                                            <td>
                                                {{ $predicted['delta_w1'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Nilai Delta W2:
                                            </td>
                                            <td>
                                                {{ $predicted['delta_w2'] }}
                                            </td>
                                        </tr> --}}
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>


    <script>
        // $(document).ready(function() {
        //     $('#myTable').DataTable();
        // });

        new DataTable('#myTable');
    </script>
@endsection
