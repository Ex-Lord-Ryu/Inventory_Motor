@extends('layouts.app')

@section('title', 'Purchase Orders')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <style>
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
                                                <a href="{{ route('purchase_orders.edit', $order->id) }}"
                                                    class="btn btn-primary btn-sm action-btn edit-btn mr-1 edit-btn"><i
                                                        class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ route('purchase_orders.delete', $order->id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm action-btn delete-btn"><i
                                                            class="fas fa-trash"></i> Delete</button>
                                                </form>
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
            // Edit confirmation
            $('.edit-btn').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');

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
                        window.location.href = href;
                    }
                });
            });

            // Delete confirmation
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

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
                        form.submit();
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
