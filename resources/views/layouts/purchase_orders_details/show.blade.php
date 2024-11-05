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
                    <h5>ID: {{ $purchaseOrderDetail->id }}</h5>
                    <p><strong>Invoice:</strong> {{ $purchaseOrderDetail->invoice }}</p>
                    <p><strong>Order:</strong> {{ $purchaseOrderDetail->order }}</p>
                    <p><strong>Jumlah:</strong> {{ $purchaseOrderDetail->jumlah }}</p>
                    <p><strong>Harga:</strong> {{ $purchaseOrderDetail->harga }}</p>
                    <p><strong>Motor:</strong> {{ $purchaseOrderDetail->motor->name }}</p>
                    <p><strong>Spare Part:</strong> {{ $purchaseOrderDetail->sparePart->name }}</p>
                    <p><strong>Created At:</strong> {{ $purchaseOrderDetail->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <a href="{{ route('purchase_orders_details.index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </section>
</div>
@endsection