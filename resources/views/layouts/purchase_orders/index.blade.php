@extends('layouts.app')

@section('title', 'Purchase Orders')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <style>
        .custom-btn-group {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .custom-btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            transition: all 0.3s ease;
            flex: 0 0 auto;
        }
        
        .btn-group .btn i {
            margin-right: 3px;
        }
        
        .btn-sm {
            min-width: 100px; /* Adjust this value as needed */
            text-align: center;
        }

        .action-btn {
            position: relative;
            overflow: hidden;
        }

        .action-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: all 0.6s;
        }

        .action-btn:hover:before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .edit-btn {
            background-color: #007bff;
            border-color: #007bff;
        }

        .cancel-btn {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .complete-btn {
            background-color: #28a745;
            border-color: #28a745;
        }

        .delete-btn {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .edit-btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .cancel-btn:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .complete-btn:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .delete-btn:hover {
            background-color: #c82333;
            border-color: #c82333;
        }

        .pagination {
            justify-content: flex-end;
        }

        .sort-icon {
            font-size: 0.8em;
            margin-left: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase Orders</h1>
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
                                <form action="{{ route('purchase_orders.index') }}" method="GET" class="d-flex w-100">
                                    <div class="input-group w-100">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by Invoice..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                                                Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('purchase_orders.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Tambah PO</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>
                                            ID
                                            <a
                                                href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'order', 'order' => $sortBy == 'order' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                <i
                                                    class="fas fa-sort{{ $sortBy == 'order' ? ($order == 'asc' ? '-up' : '-down') : '' }} sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Invoice
                                            <a
                                                href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'invoice', 'order' => $sortBy == 'invoice' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                <i
                                                    class="fas fa-sort{{ $sortBy == 'invoice' ? ($order == 'asc' ? '-up' : '-down') : '' }} sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Vendor
                                            <a
                                                href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'vendor_id', 'order' => $sortBy == 'vendor_id' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                <i
                                                    class="fas fa-sort{{ $sortBy == 'vendor_id' ? ($order == 'asc' ? '-up' : '-down') : '' }} sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>
                                            Status
                                            <a
                                                href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'status', 'order' => $sortBy == 'status' && $order == 'asc' ? 'desc' : 'asc'])) }}">
                                                <i
                                                    class="fas fa-sort{{ $sortBy == 'status' ? ($order == 'asc' ? '-up' : '-down') : '' }} sort-icon"></i>
                                            </a>
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrders as $order)
                                        <tr>
                                            <td>{{ $order->order }}</td>
                                            <td>{{ $order->invoice }}</td>
                                            <td>{{ $order->vendor->name_Vendor }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                            <td>{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>
                                                <div class="btn-group custom-btn-group" role="group" style="display: flex; flex-wrap: wrap; gap: 5px;">
                                                    @if ($order->status == 'pending')
                                                        <button type="button" class="btn btn-primary btn-sm action-btn edit-btn"
                                                            data-id="{{ $order->id }}" title="Edit"
                                                            style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                            <i class="fas fa-edit" style="margin-right: 3px;"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-sm action-btn cancel-btn"
                                                            data-id="{{ $order->id }}" title="Cancel"
                                                            style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                            <i class="fas fa-ban" style="margin-right: 3px;"></i> Cancel
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-sm action-btn complete-btn"
                                                            data-id="{{ $order->id }}" title="Complete"
                                                            style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                            <i class="fas fa-check" style="margin-right: 3px;"></i> Complete
                                                        </button>
                                                    @elseif ($order->status == 'cancelled')
                                                        <button type="button" class="btn btn-secondary btn-sm action-btn"
                                                            disabled title="Cancelled"
                                                            style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                            <i class="fas fa-ban" style="margin-right: 3px;"></i> Cancelled
                                                        </button>
                                                    @elseif ($order->status == 'completed')
                                                        <button type="button" class="btn btn-success btn-sm action-btn"
                                                            disabled title="Completed"
                                                            style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                            <i class="fas fa-check" style="margin-right: 3px;"></i> Completed
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-danger btn-sm action-btn delete-btn"
                                                        data-id="{{ $order->id }}" title="Delete"
                                                        style="min-width: 100px; text-align: center; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1.5;">
                                                        <i class="fas fa-trash" style="margin-right: 3px;"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Section -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-sm text-gray-700 leading-5">
                                    Showing {{ $purchaseOrders->firstItem() }} to {{ $purchaseOrders->lastItem() }} of
                                    {{ $purchaseOrders->total() }} results
                                </p>
                            </div>
                            <div>
                                {{ $purchaseOrders->links() }}
                            </div>
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
            // Edit button
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Edit Purchase Order',
                    text: "Apakah Anda yakin ingin mengedit data purchase order ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Edit!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('purchase_orders.edit', ':id') }}"
                            .replace(':id', id);
                    }
                });
            });

            // Delete button
            $('.delete-btn').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Purchase Order',
                    text: "Apakah Anda yakin ingin menghapus data purchase order ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_orders.delete', ':id') }}".replace(
                                ':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success').then(
                                    () => {
                                        location.reload();
                                    });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });

            function attachDeleteListener(button) {
                button.on('click', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Hapus Purchase Order',
                        text: "Apakah Anda yakin ingin menghapus data purchase order ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('purchase_orders.delete', ':id') }}"
                                    .replace(':id', id),
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    _method: 'DELETE'
                                },
                                success: function(response) {
                                    Swal.fire('Deleted!', response.message, 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                },
                                error: function(xhr) {
                                    Swal.fire('Error!', xhr.responseJSON.message,
                                        'error');
                                }
                            });
                        }
                    });
                });
            }

            // Cancel button
            // Cancel button
            $('.cancel-btn').on('click', function() {
                var id = $(this).data('id');
                var btn = $(this);
                Swal.fire({
                    title: 'Cancel Purchase Order',
                    text: "Are you sure you want to cancel this purchase order?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_orders.cancel', ':id') }}".replace(
                                ':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'PATCH'
                            },
                            success: function(response) {
                                Swal.fire('Cancelled!', response.message, 'success')
                                    .then(() => {
                                        // Update button group
                                        var btnGroup = btn.closest('.btn-group');
                                        btnGroup.html(`
                            <button type="button" class="btn btn-secondary btn-sm action-btn" disabled title="Cancelled">
                                <i class="fas fa-ban"></i> Cancelled
                            </button>
                            <button type="button" class="btn btn-danger btn-sm action-btn delete-btn" data-id="${id}" title="Delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        `);
                                        // Attach delete listener to new delete button
                                        attachDeleteListener(btnGroup.find(
                                            '.delete-btn'));
                                    });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });

            // Complete button
            $('.complete-btn').on('click', function() {
                var id = $(this).data('id');
                var btn = $(this);
                Swal.fire({
                    title: 'Complete Purchase Order',
                    text: "Are you sure you want to mark this purchase order as completed?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, complete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_orders.complete', ':id') }}".replace(
                                ':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'PATCH'
                            },
                            success: function(response) {
                                Swal.fire('Completed!', response.message, 'success')
                                    .then(() => {
                                        // Update button group
                                        var btnGroup = btn.closest('.btn-group');
                                        btnGroup.html(`
                            <button type="button" class="btn btn-success btn-sm action-btn" disabled title="Completed">
                                <i class="fas fa-check"></i> Completed
                            </button>
                            <button type="button" class="btn btn-danger btn-sm action-btn delete-btn" data-id="${id}" title="Delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        `);
                                        // Attach delete listener to new delete button
                                        attachDeleteListener(btnGroup.find(
                                            '.delete-btn'));
                                    });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush
