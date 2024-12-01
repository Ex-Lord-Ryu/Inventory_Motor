@extends('layouts.app')

@section('title', 'Edit Warna')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: none;
            padding: 20px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: 600;
            color: #34395e;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #e4e6fc;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, .25);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            border-color: #5a67d8;
        }

        .btn-secondary {
            background-color: #cdd3d8;
            border-color: #cdd3d8;
            color: #34395e;
        }

        .btn-secondary:hover {
            background-color: #bfc6cd;
            border-color: #bfc6cd;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><i class="fas fa-edit mr-2"></i>Edit Warna</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form id="warnaForm" action="{{ route('master_warna.update', $warna->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_warna">Nama Warna</label>
                                <input type="text" class="form-control @error('nama_warna') is-invalid @enderror"
                                    id="nama_warna" name="nama_warna" placeholder="Masukkan Nama Warna"
                                    value="{{ old('nama_warna', $warna->nama_warna) }}" required>
                                @error('nama_warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-save mr-1"></i> Update
                                </button>
                                <a href="{{ route('master_warna.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            $('#warnaForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Menyimpan Data',
                    text: 'Apakah Anda yakin ingin menyimpan perubahan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6777ef',
                    cancelButtonColor: '#cdd3d8',
                    confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Simpan!',
                    cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal'
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

                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data warna berhasil diperbarui.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = '{{ route('master_warna.index') }}';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menyimpan data.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush