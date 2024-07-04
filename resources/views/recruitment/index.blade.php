@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-inline card-title">{{ $title }}</h4>
                    @if (DB::table('predicted')->count() == 0)
                        <p class="d-inline alert-warning"><i class="icon-info"></i> Data Training Belum
                            dilakukan
                        </p>
                    @endif
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
                    <form action="{{ route('recruitments.import') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between mt-2">
                            <div class="col-md-8">
                                <a href="{{ route('recruitments.create') }}"
                                    class="btn btn-secondary btn-sm mb-2 text-dark"><i class="icon-plus"></i> Add
                                    Data</a>
                                @if (\DB::table('predict_data')->count() > 0 && \DB::table('recruitment')->count() > 0)
                                    <a href="{{ route('recruitments.process') }}" class="btn btn-dark btn-sm mb-2"><i
                                            class="icon-check"></i>
                                        Process
                                    </a>
                                @elseif (\DB::table('recruitment')->count() == 0)
                                    <a href="#" class="btn btn-secondary btn-sm mb-2"><i class="icon-check"></i>
                                        Process
                                    </a>
                                    Data Recruitment Belum ada
                                @else
                                    <a href="#" class="btn btn-secondary btn-sm mb-2"><i class="icon-check"></i>
                                        Process
                                    </a>
                                @endif

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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama </th>
                                <th> Membaca Not Angka </th>
                                <th> Mengoperasikan Sofware </th>
                                <th> Mengoperasikan Audio </th>
                                <th> Option </th>
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
                                    <td>
                                        <a href="{{ route('recruitments.edit', $dt->id) }}"
                                            class="btn btn-warning btn-sm text-dark">Edit</a>
                                        <form action="{{ route('recruitments.destroy', $dt->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm text-dark">Delete</button>
                                        </form>

                                    </td>

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
