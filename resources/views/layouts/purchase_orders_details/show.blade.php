@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase Order Details - Invoice: {{ $invoice }}</h1>
            </div>

            @if (isset($message))
                <div class="alert alert-info">{{ $message }}</div>
            @else
                <div class="section-body">
                    <h2 class="section-title">Motor Details</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="35%">Motor Name</th>
                                    <th width="15%">Color</th>
                                    <th width="10%">Quantity</th>
                                    <th width="20%">Price</th>
                                    <th width="20%">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($motorDetails as $detail)
                                    <tr>
                                        <td>{{ $detail['name'] }}</td>
                                        <td>{{ $detail['color'] }}</td>
                                        <td class="text-center">{{ $detail['quantity'] }}</td>
                                        <td class="text-right">Rp {{ number_format($detail['price'], 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($detail['total_price'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No motor details found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Motor Price:</th>
                                    <th class="text-right">Rp {{ number_format($motorDetails->sum('total_price'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <h2 class="section-title mt-4">Spare Part Details</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="40%">Spare Part Name</th>
                                    <th width="15%">Quantity</th>
                                    <th width="20%">Price</th>
                                    <th width="25%">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sparePartDetails as $detail)
                                    <tr>
                                        <td>{{ $detail['name'] }}</td>
                                        <td class="text-center">{{ $detail['quantity'] }}</td>
                                        <td class="text-right">Rp {{ number_format($detail['price'], 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($detail['total_price'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No spare part details found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Spare Part Price:</th>
                                    <th class="text-right">Rp
                                        {{ number_format($sparePartDetails->sum('total_price'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <h2 class="section-title mt-4">Total Keseluruhan</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="75%" class="text-right">Total Keseluruhan:</th>
                                    <td width="25%" class="text-right font-weight-bold">Rp
                                        {{ number_format($totalPrice, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tambahkan tombol kembali di sini -->
            <div class="mt-4">
                <a href="{{ route('purchase_orders_details.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </section>
    </div>
@endsection
