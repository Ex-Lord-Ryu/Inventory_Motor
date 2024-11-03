@extends('layouts.app')

@section('title', 'Tambah Purchase Order')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Purchase Order</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('purchase_orders.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" class="form-control" required>
                            <option value="">Select Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name_Vendor }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
                        </select>
                        @error('status')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                    <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back</a>
                </form>                
            </div>
        </section>
    </div>
@endsection
