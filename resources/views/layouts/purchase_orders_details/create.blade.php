@extends('layouts.app')

@section('title', 'Add Purchase Order Detail')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Purchase Order Detail</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('purchase_orders_details.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="po_id">Purchase Order</label>
                        <select name="po_id" class="form-control" required>
                            <option value="">Select Purchase Order</option>
                            @foreach ($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->invoice }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="invoice">Invoice</label>
                        <input type="text" name="invoice" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="motor_id">Motor</label>
                        <select name="motor_id" class="form-control" required>
                            <option value="">Select Motor</option>
                            @foreach ($motors as $motor)
                                <option value="{{ $motor->id }}">{{ $motor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spare_part_id">Spare Part</label>
                        <select name="spare_part_id" class="form-control" required>
                            <option value="">Select Spare Part</option>
                            @foreach ($spareParts as $sparePart)
                                <option value="{{ $sparePart->id }}">{{ $sparePart->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </section>
    </div>
@endsection
