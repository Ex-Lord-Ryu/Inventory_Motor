@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Purchase Order</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('purchase_orders.update', $purchaseOrder->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="invoice">Invoice</label>
                        <input type="text" name="invoice" class="form-control" value="{{ $purchaseOrder->invoice }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" class="form-control" required>
                            <option value="">Select Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>
                                    {{ $vendor->name_Vendor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ $purchaseOrder->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ $purchaseOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Purchase Order</button>
                    <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </section>
    </div>
@endsection
