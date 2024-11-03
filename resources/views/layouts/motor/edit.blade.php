@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Vendor</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('distributor.update', $distributor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <input type="text" name="name_Vendor" class="form-control" value="{{ $distributor->name_Vendor }}">
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ $distributor->telepon }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $distributor->alamat }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('distributor.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </section>
    </div>
@endsection
