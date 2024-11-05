@extends('layouts.app')

@section('title', 'Purchase Orders')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase Orders</h1>
            </div>
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="section-body">
                <div class="table-responsive">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('purchase_orders.index') }}" method="GET" class="d-flex w-100">
                                <div class="input-group w-100">
                                    <input type="text" name="search" class="form-control" placeholder="Search by Invoice..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary ml-2">Tambah PO</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                    <a href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'order', 'order' => ($sortBy == 'order' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'order')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    Invoice
                                    <a href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'invoice', 'order' => ($sortBy == 'invoice' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'invoice')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    Vendor
                                    <a href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'vendor_id', 'order' => ($sortBy == 'vendor_id' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'vendor_id')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>
                                    Status
                                    <a href="{{ route('purchase_orders.index', array_merge(request()->all(), ['sortBy' => 'status', 'order' => ($sortBy == 'status' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'status')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
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
                                        <a href="{{ route('purchase_orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('purchase_orders.delete', $order->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                                Showing
                                <span class="font-medium">{{ $purchaseOrders->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $purchaseOrders->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $purchaseOrders->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            @if ($purchaseOrders->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                                    « Previous
                                </span>
                            @else
                                <a href="{{ $purchaseOrders->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    « Previous
                                </a>
                            @endif

                            @if ($purchaseOrders->hasMorePages())
                                <a href="{{ $purchaseOrders->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    Next »
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                                    Next »
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <!-- Page Specific JS File -->
@endpush
