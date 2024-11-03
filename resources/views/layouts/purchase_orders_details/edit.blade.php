@extends('layouts.app')

@section('title', 'Edit Purchase Order Detail')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Purchase Order Detail</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('purchase_orders_details.update', $purchaseOrderDetail->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="po_id">Purchase Order</label>
                        <select name="po_id" class="form-control" required>
                            <option value="">Select Purchase Order</option>
                            @foreach ($purchaseOrders as $po)
                                <option value="{{ $po->id }}" {{ $po->id == $purchaseOrderDetail->po_id ? 'selected' : '' }}>
                                    {{ $po->invoice }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="invoice">Invoice</label>
                        <input type="text" name="invoice" class="form-control" value="{{ $purchaseOrderDetail->invoice }}" required>
                    </div>
                    <div class="form-group">
                        <label for="motor_id">Motor</label>
                        <select name="motor_id" class="form-control" required>
                            <option value="">Select Motor</option>
                            @foreach ($motors as $motor)
                                <option value="{{ $motor->id }}" {{ $motor->id == $purchaseOrderDetail->motor_id ? 'selected' : '' }}>
                                    {{ $motor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spare_part_id">Spare Part</label>
                        <select name="spare_part_id" class="form-control" required>
                            <option value="">Select Spare Part</option>
                            @foreach ($spareParts as $sparePart)
                                <option value="{{ $sparePart->id }}" {{ $sparePart->id == $purchaseOrderDetail->spare_part_id ? 'selected' : '' }}>
                                    {{ $sparePart->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </section>
    </div>
@endsection
