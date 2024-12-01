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
                        <form action="{{ route('stock.store-motor-data') }}" method="POST" id="motorDataForm">
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
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        $('form').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Menyimpan Data',
                text: 'Apakah Anda yakin ingin menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data motor berhasil disimpan.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href =
                                    '{{ route('stock.index') }}';
                            });
                        },
                        error: function(xhr) {
                            let errorMessage =
                                'Terjadi kesalahan saat menyimpan data.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON
                                    .errors).join('\n');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
