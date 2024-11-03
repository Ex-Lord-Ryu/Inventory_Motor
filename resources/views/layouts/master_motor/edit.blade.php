@extends('layouts.app')

@section('title', 'Edit Motor')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Motor</h1>
            </div>

            <div class="section-body">
                <form action="{{ route('master_motor.update', $motor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_motor">Nama Motor</label>
                        <input type="text" class="form-control @error('nama_motor') is-invalid @enderror" id="nama_motor" name="nama_motor" placeholder="Masukkan Nama Motor" value="{{ old('nama_motor', $motor->nama_motor) }}" required>
                        @error('nama_motor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
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
