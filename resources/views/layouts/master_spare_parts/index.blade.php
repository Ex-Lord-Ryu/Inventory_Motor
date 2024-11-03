@extends('layouts.app')

@section('title', 'Master Spare Parts')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Master Spare Parts</h1>
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
                            <form action="{{ route('master_spare_parts.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari Berdasarkan ID...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                    <a href="{{ route('master_spare_parts.create') }}" class="btn btn-primary ml-2">Tambah
                                        Spare Parts</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                    <a href="{{ route('master_spare_parts.index', array_merge(request()->all(), ['sortBy' => 'order', 'order' => ($sortBy == 'order' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'order')
                                            {{ $order == 'asc' ? '▲' : '▼' }}
                                        @else
                                            ▼▲
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    Nama Spare Part
                                    <a href="{{ route('master_spare_parts.index', array_merge(request()->all(), ['sortBy' => 'nama_spare_part', 'order' => ($sortBy == 'nama_spare_part' && $order == 'asc') ? 'desc' : 'asc'])) }}">
                                        @if ($sortBy == 'nama_spare_part')
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
                            @foreach ($master_spare_parts as $item)
                                <tr>
                                    <td>{{ $item->order }}</td>
                                    <td>{{ $item->nama_spare_part }}</td>
                                    <td>
                                        <a href="{{ route('master_spare_parts.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('master_spare_parts.delete', $item->id) }}" method="POST" style="display: inline-block;">
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
                                <span class="font-medium">{{ $master_spare_parts->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $master_spare_parts->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $master_spare_parts->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            @if ($master_spare_parts->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                                    « Previous
                                </span>
                            @else
                                <a href="{{ $master_spare_parts->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    « Previous
                                </a>
                            @endif

                            @if ($master_spare_parts->hasMorePages())
                                <a href="{{ $master_spare_parts->nextPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    Next »
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
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
