@extends('layouts.app')

@section('title', 'Tambah Warna')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Warna</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('master_warna.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama_warna">Nama Warna</label>
                    <input type="text" name="nama_warna" id="nama_warna" class="form-control" placeholder="Masukkan Nama Warna" required>
                    @error('nama_warna')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master_warna.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </section>
</div>
@endsection
