@extends('layouts.app')

@section('title', 'Purchase Order Detail')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Purchase Order Detail</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @if(isset($purchaseOrderDetails) && !$purchaseOrderDetails->isEmpty())
                        <table class="table table-bordered mt-3">
                            <tr>
                                <th>ID</th>
                                <th>Purchase Order ID</th>
                                <th>Invoice</th>
                            </tr>
                            @foreach($purchaseOrderDetails as $detail)
                                <tr>
                                    <td>{{ $detail->id }}</td>
                                    <td>{{ $detail->purchaseOrder->id ?? 'N/A' }}</td>
                                    <td>{{ $detail->invoice }}</td>
                                </tr>
                            @endforeach
                        </table>

                        <h4>Motor</h4>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Motor</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrderDetails as $index => $detail)
                                    @if($detail->motor)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->motor->nama_motor }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>{{ $detail->harga }}</td>
                                            <td>{{ $detail->total_harga }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <h4>Spare Part</h4>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Spare Part</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrderDetails as $index => $detail)
                                    @if($detail->sparePart)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->sparePart->nama_spare_part }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>{{ $detail->harga }}</td>
                                            <td>{{ $detail->total_harga }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4>{{ $message }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
