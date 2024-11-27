@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Sales Report</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('sales_report.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="month" class="form-control">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="year" class="form-control">
                                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="orderMotors" name="tables[]"
                                            value="orderMotors">
                                        <label class="form-check-label" for="orderMotors">Order Motors</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="orderSpareParts" name="tables[]"
                                            value="orderSpareParts">
                                        <label class="form-check-label" for="orderSpareParts">Order Spare Parts</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="soldMotors" name="tables[]"
                                            value="soldMotors">
                                        <label class="form-check-label" for="soldMotors">Sold Motors</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="soldSpareParts" name="tables[]"
                                            value="soldSpareParts">
                                        <label class="form-check-label" for="soldSpareParts">Sold Spare Parts</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <button type="submit" name="export" value="excel" class="btn btn-success">Export
                                        Excel</button>
                                    <button type="submit" name="export" value="pdf" class="btn btn-danger">Export
                                        PDF</button>
                                </div>
                            </div>
                        </form>

                        @if (isset($reportData['orderMotors']) && count($reportData['orderMotors']) > 0)
                            <h4>Order Motors</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Motor</th>
                                        <th>Color</th>
                                        <th>Frame Number</th>
                                        <th>Engine Number</th>
                                        <th>Quantity</th>
                                        <th>Selling Price</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData['orderMotors'] as $orderMotor)
                                        <tr>
                                            <td>{{ $orderMotor->user->name }}</td>
                                            <td>{{ $orderMotor->motor->nama_motor }}</td>
                                            <td>{{ $orderMotor->warna->nama_warna }}</td>
                                            <td>{{ $orderMotor->nomor_rangka }}</td>
                                            <td>{{ $orderMotor->nomor_mesin }}</td>
                                            <td>{{ $orderMotor->jumlah }}</td>
                                            <td>{{ number_format($orderMotor->harga_jual, 2) }}</td>
                                            <td>{{ $orderMotor->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if (isset($reportData['orderSpareParts']) && count($reportData['orderSpareParts']) > 0)
                            <h4>Order Spare Parts</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Spare Part</th>
                                        <th>Quantity</th>
                                        <th>Selling Price</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData['orderSpareParts'] as $orderSparePart)
                                        <tr>
                                            <td>{{ $orderSparePart->user->name }}</td>
                                            <td>{{ $orderSparePart->sparePart->nama_spare_part }}</td>
                                            <td>{{ $orderSparePart->jumlah }}</td>
                                            <td>{{ number_format($orderSparePart->harga_jual, 2) }}</td>
                                            <td>{{ $orderSparePart->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if (isset($reportData['soldMotors']) && count($reportData['soldMotors']) > 0)
                            <h4>Sold Motors</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Motor</th>
                                        <th>Color</th>
                                        <th>Frame Number</th>
                                        <th>Engine Number</th>
                                        <th>Selling Price</th>
                                        <th>Sold Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData['soldMotors'] as $soldMotor)
                                        <tr>
                                            <td>{{ $soldMotor->motor->nama_motor }}</td>
                                            <td>{{ $soldMotor->warna->nama_warna }}</td>
                                            <td>{{ $soldMotor->nomor_rangka }}</td>
                                            <td>{{ $soldMotor->nomor_mesin }}</td>
                                            <td>{{ number_format($soldMotor->harga_jual, 2) }}</td>
                                            <td>{{ $soldMotor->tanggal_terjual }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if (isset($reportData['soldSpareParts']) && count($reportData['soldSpareParts']) > 0)
                            <h4>Sold Spare Parts</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Spare Part</th>
                                        <th>Quantity</th>
                                        <th>Selling Price</th>
                                        <th>Sold Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData['soldSpareParts'] as $soldSparePart)
                                        <tr>
                                            <td>{{ $soldSparePart->sparePart->nama_spare_part }}</td>
                                            <td>{{ $soldSparePart->jumlah }}</td>
                                            <td>{{ number_format($soldSparePart->harga_jual, 2) }}</td>
                                            <td>{{ $soldSparePart->tanggal_terjual }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
