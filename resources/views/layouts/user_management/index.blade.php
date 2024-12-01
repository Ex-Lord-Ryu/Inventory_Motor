@extends('layouts.app')

@section('title', 'User Management')

@push('style')
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #f8f9fa;
        }

        .btn-action {
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            border-radius: 4px;
        }

        .page-link {
            padding: 0.3rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            margin: 0 2px;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><i class="fas fa-users mr-2"></i>User Management</h1>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('user_management.index') }}" method="GET" class="d-flex">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari Berdasarkan ID..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                                                Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user_management as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->role }}</td>
                                            <td>
                                                <a href="{{ route('user_management.edit', $item->id) }}"
                                                    class="btn btn-sm btn-primary btn-action mr-1"><i
                                                        class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ route('user_management.delete', $item->id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-action delete-btn"
                                                        data-id="{{ $item->id }}"><i class="fas fa-trash"></i>
                                                        Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var userId = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Apakah Anda yakin ingin menghapus data user ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
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
                            url: "{{ route('user_management.delete', '') }}/" + userId,
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data user berhasil dihapus.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menghapus data.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
