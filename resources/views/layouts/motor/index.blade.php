@extends('layouts.app')

@section('title', 'Vendor')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Vendor</h1>
            </div>
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="section-body">
                <div class="table-responsive">
                    <div class="row mb-3">
                        <<div class="col-md-6">
                            <div class="row mb-3">
                                <form action="{{ route('distributor.index') }}" method="GET" class="d-flex w-50">
                                    <div class="input-group w-100">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Berdasarkan ID...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
                                        <a href="{{ route('distributor.create') }}" class="btn btn-primary ml-2">Tambah Vendor</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($distributor as $item)
                                    <tr>
                                        <td>{{ $item->order }}</td>
                                        <td>{{ $item->name_Vendor }}</td>
                                        <td>{{ $item->telepon }}</td>
                                        <td>{{ $item->alamat }}</td>

                                        <td>
                                            <a href="{{ route('distributor.edit', $item->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <form action="{{ route('distributor.delete', $item->id) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <!-- Page Specific JS File -->
@endpush
