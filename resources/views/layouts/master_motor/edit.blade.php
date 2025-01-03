@extends('layouts.app')

@section('title', 'Edit Motor')

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
            box-shadow: 0 0 0 0.2rem rgba(103,119,239,.25);
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
                <h1><i class="fas fa-edit mr-2"></i>Edit Motor</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Edit Motor</h4>
                    </div>
                    <div class="card-body">
                        <form id="motorForm" action="{{ route('master_motor.update', $motor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_motor">Nama Motor</label>
                                <input type="text" class="form-control @error('nama_motor') is-invalid @enderror" id="nama_motor" name="nama_motor" placeholder="Masukkan Nama Motor" value="{{ old('nama_motor', $motor->nama_motor) }}" required>
                                @error('nama_motor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-save mr-1"></i> Update
                                </button>
                                <a href="{{ route('master_motor.index') }}" class="btn btn-secondary">
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
            $('#motorForm').on('submit', function(e) {
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
                                    text: 'Data motor berhasil diperbarui.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = '{{ route('master_motor.index') }}';
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