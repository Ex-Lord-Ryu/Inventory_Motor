@extends('layouts.app')

@section('title', 'Input Motor Data')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Input Motor Data</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stock.store-motor-data') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="stock_id">Pilih Motor</label>
                                <select name="stock_id" id="stock_id" class="form-control select2" required>
                                    @foreach ($stockMotors as $stock)
                                        <option value="{{ $stock->id }}">
                                            {{ $stock->motor->nama_motor }} - {{ $stock->warna->nama_warna }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nomor_rangka">Nomor Rangka</label>
                                <input type="text" name="nomor_rangka" id="nomor_rangka" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nomor_mesin">Nomor Mesin</label>
                                <input type="text" name="nomor_mesin" id="nomor_mesin" class="form-control" required>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
