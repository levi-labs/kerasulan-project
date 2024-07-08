@extends('layouts.master')
@section('content')
    <div class="row">
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

                    <form action="{{ route('training-data.import') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between">
                            <div class="col-md-2">
                                <a href="{{ route('training-data.process') }}" class="btn btn-dark btn-sm float-left"><i
                                        class="icon-check"></i> Proses</a>
                            </div>
                            <div class="input-group col-md-3">
                                <input type="file" class="form-control form-control-sm" placeholder="Upload File"
                                    name="file">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-dark btn-sm" type="submit">Upload</button>
                                </span>
                            </div>
                        </div>
                    </form>






                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama </th>
                                <th> Membaca Not Angka </th>
                                <th> Mengoperasikan Sofware </th>
                                <th> Mengoperasikan Audio </th>
                                <th> Bidang </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt)
                                <tr>
                                    <td> {{ $dt->id }} </td>
                                    <td> {{ $dt->nama }} </td>
                                    <td> {{ $dt->membaca_not_angka }} </td>
                                    <td> {{ $dt->mengoperasikan_software }} </td>
                                    <td> {{ $dt->mengoperasikan_audio }} </td>
                                    <td> {{ $dt->bidang }}</td>
                                    {{-- <td>
                                        <a href="{{ route('recruitments.show', $dt->id) }}"
                                            class="btn btn-dark btn-sm">Show</a>
                                    </td> --}}
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
