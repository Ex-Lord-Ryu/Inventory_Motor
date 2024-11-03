@extends('layouts.app')

@section('title', 'Tambah Motor')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Motor</h1>
            </div>

            <div class="section-body">
                <form action="{{ route('master_motor.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_motor">Nama Motor</label>
                        <input type="text" class="form-control @error('nama_motor') is-invalid @enderror" id="nama_motor" name="nama_motor" placeholder="Masukkan Nama Motor" value="{{ old('nama_motor') }}" required>
                        @error('nama_motor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('master_motor.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <!-- Page Specific JS File -->
@endpush
