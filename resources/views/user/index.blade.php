@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
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
                    <a href="{{ route('users.create') }}" class="btn btn-dark btn-sm mb-2"><i class="icon-plus"></i> Add
                        Data</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Username </th>
                                <th> Name </th>
                                <th> Role </th>
                                <th> Option </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $dt->username }} </td>
                                    <td> {{ $dt->name }} </td>
                                    <td> {{ $dt->role }} </td>
                                    <td>
                                        <a href="{{ route('users.edit', $dt->id) }}"
                                            class="btn btn-warning text-dark btn-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $dt->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger text-dark btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
