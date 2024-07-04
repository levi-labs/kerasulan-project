@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <a href="{{ route('recruitments.create') }}" class="btn btn-dark btn-sm mb-2"><i class="icon-plus"></i> Add
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
                                    <td> {{ $dt->id }} </td>
                                    <td> {{ $dt->name }} </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td> $ 77.99 </td>
                                    <td>
                                        <a href="{{ route('recruitments.show', $dt->id) }}"
                                            class="btn btn-dark btn-sm">Show</a>
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
