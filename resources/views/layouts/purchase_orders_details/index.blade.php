@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase Order Details</h1>
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
                            <form action="{{ route('purchase_orders_details.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by ID...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                    <a href="{{ route('purchase_orders_details.create') }}" class="btn btn-primary ml-2">Tambah PO Details</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                    <a href="{{ route('purchase_orders_details.index', array_merge(request()->all(), ['sortBy' => 'id', 'order' => ($sortBy == 'id' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'id')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    Invoice
                                    <a href="{{ route('purchase_orders_details.index', array_merge(request()->all(), ['sortBy' => 'invoice', 'order' => ($sortBy == 'invoice' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'invoice')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrdersDetails as $detail)
                                <tr>
                                    <td>{{ $detail->id }}</td>
                                    <td>{{ $detail->invoice }}</td>
                                    <td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('purchase_orders_details.show', $detail->id) }}" class="btn btn-primary">show</a>
                                        <form action="{{ route('purchase_orders_details.delete', $detail->id) }}" method="POST" style="display: inline-block;">
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
                                <span class="font-medium">{{ $purchaseOrdersDetails->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $purchaseOrdersDetails->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $purchaseOrdersDetails->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            @if ($purchaseOrdersDetails->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                                    « Previous
                                </span>
                            @else
                                <a href="{{ $purchaseOrdersDetails->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500">
                                    « Previous
                                </a>
                            @endif

                            @if ($purchaseOrdersDetails->hasMorePages())
                                <a href="{{ $purchaseOrdersDetails->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500">
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
