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
                    <form class="forms-sample" method="POST" action="{{ route('users.update', $user->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" placeholder="Nama Lengkap"
                                name="nama" value="{{ $user->name }}">
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="John Doe" name="username"
                                value="{{ $user->username }}">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="JohnDoe@gmail.com"
                                name="email" value="{{ $user->email }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Role</label>
                            <select class="form-control" id="exampleSelectGender" name="role">
                                <option selected disabled>Pilih Akses Level User</option>
                                <option {{ $user->role == 'admin' ? ' selected' : '' }} value="admin">Admin
                                </option>
                                <option {{ $user->role == 'staf' ? ' selected' : '' }} value="staf">Staf</option>
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm btn-dark mr-2">Submit</button>
                        <button class="btn btn-sm btn-light" type="button"
                            onclick="window.location='{{ route('users.index') }}'">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
