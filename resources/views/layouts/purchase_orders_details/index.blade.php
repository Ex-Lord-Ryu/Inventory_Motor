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
                                    <input type="text" name="search" class="form-control" placeholder="Cari Berdasarkan ID...">
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
                                <th>ID</th>
                                <th>Invoice</th>
                                <th>Motor</th>
                                <th>Spare Part</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrdersDetails as $detail)
                                <tr>
                                    <td>{{ $detail->id }}</td>
                                    <td>{{ $detail->invoice }}</td>
                                    <td>{{ $detail->motor->name ?? 'N/A' }}</td>
                                    <td>{{ $detail->sparePart->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('purchase_orders_details.edit', $detail->id) }}" class="btn btn-primary">Edit</a>
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
                </div>
            </div>
        </section>
    </div>
@endsection
