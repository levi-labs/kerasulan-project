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
                    <form class="forms-sample" method="POST"
                        action="{{ route('users.update-password', auth()->user()->id) }}">
                        @method('PUT')
                        @csrf

                        <div class="form-group">
                            <label for="password_lama">Password Lama</label>
                            <input type="password" class="form-control" id="password_lama" name="password_lama">
                            @error('password_lama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password_baru">
                            @error('password_baru')
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
