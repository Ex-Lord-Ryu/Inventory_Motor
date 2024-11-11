@extends('layouts.app')

@section('title', 'Stocks')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Stocks</h1>
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('stock.index') }}" method="GET" class="d-flex w-100">
                            <div class="input-group w-100">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>In</option>
                                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Out</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <h3>Motors</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Type</th>
                                <th>Tanggal Masuk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($motors as $motor)
                                <tr>
                                    <td>{{ $motor->order }}</td>
                                    <td>{{ $motor->type }}</td>
                                    <td>{{ $motor->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#motorModal{{ $motor->id }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3 class="mt-5">Spare Parts</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Type</th>
                                <th>Tanggal Masuk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spareParts as $sparePart)
                                <tr>
                                    <td>{{ $sparePart->order }}</td>
                                    <td>{{ $sparePart->type }}</td>
                                    <td>{{ $sparePart->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sparePartModal{{ $sparePart->id }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Section -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                            Showing {{ $motors->count() + $spareParts->count() }} results
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Motor Modals -->
    @foreach ($motors as $motor)
        <div class="modal fade" id="motorModal{{ $motor->id }}" tabindex="-1" role="dialog" aria-labelledby="motorModalLabel{{ $motor->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="motorModalLabel{{ $motor->id }}">Motor Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Order:</strong> {{ $motor->order }}</p>
                        <p><strong>Type:</strong> {{ $motor->type }}</p>
                        <p><strong>Jumlah:</strong> {{ $motor->jumlah }}</p>
                        <p><strong>Harga Beli:</strong> {{ number_format($motor->harga_beli, 2) }}</p>
                        <p><strong>Harga Jual:</strong> {{ number_format($motor->harga_jual, 2) }}</p>
                        <p><strong>Harga Jual Diskon:</strong> {{ number_format($motor->harga_jual_diskon, 2) }}</p>
                        <p><strong>Nomor Rangka:</strong> {{ $motor->nomor_rangka }}</p>
                        <p><strong>Nomor Mesin:</strong> {{ $motor->nomor_mesin }}</p>
                        <p><strong>Tanggal Masuk:</strong> {{ $motor->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Terakhir Diupdate:</strong> {{ $motor->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Spare Part Modals -->
    @foreach ($spareParts as $sparePart)
        <div class="modal fade" id="sparePartModal{{ $sparePart->id }}" tabindex="-1" role="dialog" aria-labelledby="sparePartModalLabel{{ $sparePart->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sparePartModalLabel{{ $sparePart->id }}">Spare Part Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Order:</strong> {{ $sparePart->order }}</p>
                        <p><strong>Type:</strong> {{ $sparePart->type }}</p>
                        <p><strong>Jumlah:</strong> {{ $sparePart->jumlah }}</p>
                        <p><strong>Harga Beli:</strong> {{ number_format($sparePart->harga_beli, 2) }}</p>
                        <p><strong>Harga Jual:</strong> {{ number_format($sparePart->harga_jual, 2) }}</p>
                        <p><strong>Harga Jual Diskon:</strong> {{ number_format($sparePart->harga_jual_diskon, 2) }}</p>
                        <p><strong>Tanggal Masuk:</strong> {{ $sparePart->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Terakhir Diupdate:</strong> {{ $sparePart->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush