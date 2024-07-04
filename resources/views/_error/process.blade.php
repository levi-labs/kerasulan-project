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
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th> Epoch</th>
                                <th> # </th>
                                <th> Nama </th>
                                <th> X1 </th>
                                <th> X2 </th>
                                <th> V </th>
                                <th> Luaran Y </th>
                                <th> Y </th>
                                <th> Error </th>
                                <th> W-1 Baru </th>
                                <th> W-2 Baru </th>
                                <th> Delta W-1</th>
                                <th> Delta W-2 </th>
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
                                <td rowspan="6"
                                    class="text-center font-weight-bold {{ $key % 2 == 0 ? 'bg-light' : 'bg-gray' }}">
                                    {{ $loop->iteration }}
                                    @foreach ($item as $result)
                                        <tr class="{{ $key % 2 == 0 ? 'bg-light' : 'bg-gray' }}">

                                            <td> {{ $loop->iteration }} </td>
                                            <td> {{ $result['nama'] }} </td>
                                            <td> {{ $result['pecah_suara'] }} </td>
                                            <td> {{ $result['audio_video'] }} </td>
                                            <td> {{ $result['v'] }} </td>
                                            @if ($result['y'] != $result['y_target'])
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
                                            <td> {{ $result['delta_w2'] }} </td>

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
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <form class="forms-sample" method="POST" action="{{ route('data-training.predicted') }}">
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
                                    <label for="nik">Pecah Suara</label>
                                    <input type="text" class="form-control" id="nik" name="pecah_suara"
                                        placeholder="Value Pecah Suara">
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nik">Audio / Video</label>
                                    <input type="text" class="form-control" id="nik" name="audio_video"
                                        placeholder="Value Audio / Video">
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-sm btn-dark mr-2">Submit</button>
                                <button class="btn btn-sm btn-light" type="button"
                                    onclick="window.location='{{ route('recruitments.index') }}'">Cancel</button>
                            </form>
                        </div>
                    </div>


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
                                    height: 52% !important;
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
                                    @if ($predicted['y_luaran'] == 1)
                                        <button type="button" class="btn bg-gradient-one bidang mt-5 ">
                                            <i class="icon-music-tone my-size text-color"></i>
                                            <i class="icon-microphone my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        <h3 class="font-weight-bold">Choir</h3>
                                    @elseif($predicted['y_luaran'] == 0)
                                        <button type="button" class="btn bg-gradient-one bidang mt-5">
                                            <i class="icon-music-tone-alt my-size text-color"></i>&nbsp;
                                            <i class="icon-screen-desktop my-size text-color"></i>
                                        </button>
                                        <h6 class="mt-5">Kelas Bidang Terprediksi : </h6>
                                        <h3 class="font-weight-bold">Multimedia</h3>
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
                                                Nilai Pecah Suara:
                                            </td>
                                            <td>{{ $predicted['x1'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai Audio / Video:</td>
                                            <td>{{ $predicted['x2'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai V:</td>
                                            <td>{{ $predicted['v'] }}</td>
                                        </tr>
                                        <tr>
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
                                        </tr>
                                        <tr>
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
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
