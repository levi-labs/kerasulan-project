@extends('layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ $title }}</h4>
                    @if (session('failed'))
                        <div class="alert alert-danger text-danger font-weight-bold" role="alert">
                            {{ session('failed') }}
                        </div>
                    @endif
                    <form class="forms-sample" method="POST" action="{{ route('recruitments.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap"
                                name="nama_lengkap">
                            @error('nama_lengkap')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="not_angka">Kemampuan Membaca Not Angka</label>
                            <input type="number" class="form-control" id="not_angka" placeholder="0" name="not_angka"
                                min="0">
                            @error('not_angka')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="software">Kemampuan Mengoperasikan Software</label>
                            <input type="number" class="form-control" id="software" placeholder="0" name="software"
                                min="0">
                            @error('software')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="audio">Kemampuan Mengoperasikan Audio</label>
                            <input type="number" class="form-control" id="software" placeholder="0" name="audio"
                                min="0">
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
        </div>
    </div>
@endsection
