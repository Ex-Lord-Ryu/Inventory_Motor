@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Vendor</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('distributor.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <input type="text" name="name_Vendor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('distributor.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </section>
    </div>
@endsection
