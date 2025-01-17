@extends('layouts.app')

@section('title', 'Master Vendor')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
@endpush

@section('content')
    <style>
         .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-wrapper {
            min-width: 1150px;
        }

        .table {
            table-layout: fixed;
            width: 100%;
        }

        /* ID column */
        .table th:nth-child(1) {
            width: 85px;
        }

        /* Name Vendor */
        .table th:nth-child(2) {
            width: 300px;
        }

        /* Telepon column */
        .table th:nth-child(3) {
            width: 180px;
        }

        /* Alamat column */
        .table th:nth-child(4) {
            width: 350px;
        }

        /* Action column - will be flexible */
        .table th:nth-child(5) {
            width: auto;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.05);
        }

        .pagination {
            justify-content: flex-end;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Master Vendor</h1>
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
                                <form action="{{ route('distributor.index') }}" method="GET" class="d-flex">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                                                Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('distributor.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Tambah Vendor</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div class="table-wrapper">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                ID
                                                <a
                                                    href="{{ route('distributor.index', array_merge(request()->all(), ['sortBy' => 'id', 'order' => $sortBy == 'id' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                    <i
                                                        class="fas fa-sort{{ $sortBy == 'id' ? ($order == 'asc' ? '-up' : '-down') : '' }}"></i>
                                                </a>
                                            </th>
                                            <th>
                                                Name Vendor
                                                <a
                                                    href="{{ route('distributor.index', array_merge(request()->all(), ['sortBy' => 'name_Vendor', 'order' => $sortBy == 'name_Vendor' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                    <i
                                                        class="fas fa-sort{{ $sortBy == 'name_Vendor' ? ($order == 'asc' ? '-up' : '-down') : '' }}"></i>
                                                </a>
                                            </th>
                                            <th>
                                                Telepon
                                                <a
                                                    href="{{ route('distributor.index', array_merge(request()->all(), ['sortBy' => 'telepon', 'order' => $sortBy == 'telepon' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                    <i
                                                        class="fas fa-sort{{ $sortBy == 'telepon' ? ($order == 'asc' ? '-up' : '-down') : '' }}"></i>
                                                </a>
                                            </th>
                                            <th>
                                                Alamat
                                                <a
                                                    href="{{ route('distributor.index', array_merge(request()->all(), ['sortBy' => 'alamat', 'order' => $sortBy == 'alamat' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                    <i
                                                        class="fas fa-sort{{ $sortBy == 'alamat' ? ($order == 'asc' ? '-up' : '-down') : '' }}"></i>
                                                </a>
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributor as $item)
                                            <tr>
                                                <td>{{ ($distributor->currentPage() - 1) * $distributor->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->name_Vendor }}</td>
                                                <td>{{ $item->telepon }}</td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>
                                                    <a href="{{ route('distributor.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm action-btn mr-1 edit-btn">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('distributor.delete', $item->id) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm action-btn delete-btn">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer text-right">
                            <nav class="d-inline-block">
                                {{ $distributor->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </nav>
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
            // Edit confirmation
            $('.edit-btn').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');

                Swal.fire({
                    title: 'Edit Vendor',
                    text: "Apakah Anda yakin ingin mengedit data vendor ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Edit!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            // Delete confirmation
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Hapus Vendor',
                    text: "Apakah Anda yakin ingin menghapus data vendor ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
