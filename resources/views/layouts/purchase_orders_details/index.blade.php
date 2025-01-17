@extends('layouts.app')

@section('title', 'Purchase Order Details')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #6777ef;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .btn-action {
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase Order Details</h1>
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
                                <form action="{{ route('purchase_orders_details.index') }}" method="GET" class="d-flex">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search..."
                                            value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                                                Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('purchase_orders_details.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Tambah PO Details</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Invoice</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrdersDetails as $detail)
                                        <tr>
                                            <td>{{ ($purchaseOrdersDetails->currentPage() - 1) * $purchaseOrdersDetails->perPage() + $loop->iteration }}</td>
                                            <td>{{ $detail->invoice }}</td>
                                            <td>{{ $detail->created_at ? $detail->created_at->format('Y-m-d H:i') : 'N/A' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $detail->status == 'cancelled' ? 'danger' : 'success' }}">
                                                    {{ $detail->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('purchase_orders_details.show', $detail->id) }}"
                                                    class="btn btn-info btn-sm btn-action"><i class="fas fa-eye"></i>
                                                    Show</a>

                                                <button type="button" class="btn btn-danger btn-sm btn-action cancel-btn"
                                                    data-id="{{ $detail->id }}" data-invoice="{{ $detail->invoice }}"
                                                    data-status="{{ $detail->status }}">
                                                    <i class="fas fa-ban"></i> Cancel
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm btn-action delete-btn"
                                                    data-id="{{ $detail->id }}" data-status="{{ $detail->status }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="card-footer text-right">
                            <nav class="d-inline-block">
                                {{ $purchaseOrdersDetails->appends(request()->query())->links('pagination::bootstrap-4') }}
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
            $('.cancel-btn').on('click', function() {
                var id = $(this).data('id');
                var invoice = $(this).data('invoice');
                var status = $(this).data('status');

                if (status === 'completed') {
                    Swal.fire(
                        'Cannot Cancel',
                        'Completed orders cannot be cancelled.',
                        'error'
                    );
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to cancel all order details with invoice ${invoice}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_orders_details.cancel', ':id') }}"
                                .replace(':id', id),
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Cancelled!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message ||
                                    'There was an error cancelling the order details.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            $('.delete-btn').on('click', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');

                if (status === 'completed') {
                    Swal.fire(
                        'Cannot Delete',
                        'Completed order details cannot be deleted.',
                        'error'
                    );
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_orders_details.delete', ':id') }}"
                                .replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message ||
                                    'There was an error deleting the order detail.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Add confirm dialog to all delete forms
            $('form').submit(function(e) {
                var form = this;
                if ($(this).find('button[type="submit"]').hasClass('delete-btn')) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endpush
