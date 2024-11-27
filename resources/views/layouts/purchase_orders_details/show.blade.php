@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Purchase Order Details</h1>
        </div>
        
        @if (isset($message))
            <div class="alert alert-info">{{ $message }}</div>
        @else
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Invoice: {{ $invoice }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Motor Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Motor Name</th>
                                                <th>Color</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($motorDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail['name'] }}</td>
                                                    <td><span class="badge badge-primary">{{ $detail['color'] }}</span></td>
                                                    <td>{{ $detail['quantity'] }}</td>
                                                    <td>Rp {{ number_format($detail['price'], 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail['total_price'], 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No motor details found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">Total Motor Price:</th>
                                                <th>Rp {{ number_format($motorDetails->sum('total_price'), 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card card-secondary mt-4">
                            <div class="card-header">
                                <h4>Spare Part Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Spare Part Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($sparePartDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail['name'] }}</td>
                                                    <td>{{ $detail['quantity'] }}</td>
                                                    <td>Rp {{ number_format($detail['price'], 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail['total_price'], 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No spare part details found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total Spare Part Price:</th>
                                                <th>Rp {{ number_format($sparePartDetails->sum('total_price'), 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card card-success mt-4">
                            <div class="card-header">
                                <h4>Total Keseluruhan</h4>
                            </div>
                            <div class="card-body">
                                <h3 class="text-right">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('purchase_orders_details.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection