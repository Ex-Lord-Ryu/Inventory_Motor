@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
    <style>
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0px;
        }

        .button-group .btn {
            flex: 0 1 auto;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .button-group {
                justify-content: flex-start;
            }
        }
    </style>

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
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @foreach ($allTables as $table)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="{{ $table }}"
                                                name="tables[]" value="{{ $table }}"
                                                {{ in_array($table, $tables) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="{{ $table }}">{{ $tableNames[$table] }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="button-group d-flex flex-wrap">
                                        <button type="submit" class="btn btn-primary mr-2 mb-2">Filter</button>
                                        <button type="submit" name="export" value="excel"
                                            class="btn btn-success mr-2 mb-2">Export Excel</button>
                                        <button type="submit" name="export" value="pdf"
                                            class="btn btn-danger mb-2">Export PDF</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @foreach ($allTables as $table)
                            @if (in_array($table, $tables) && isset($reportData[$table]) && count($reportData[$table]) > 0)
                                <h4>{{ $tableNames[$table] }}</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            @switch($table)
                                                @case('orderMotors')
                                                    <th>User</th>
                                                    <th>Motor</th>
                                                    <th>Warna</th>
                                                    <th>Nomor Rangka</th>
                                                    <th>Nomor Mesin</th>
                                                    <th>Quantity</th>
                                                    <th>Harga Jual</th>
                                                    <th>Tanggal Order</th>
                                                @break

                                                @case('orderSpareParts')
                                                    <th>User</th>
                                                    <th>Spare Part</th>
                                                    <th>Quantity</th>
                                                    <th>Harga Jual</th>
                                                    <th>Tanggal Order</th>
                                                @break

                                                @case('soldMotors')
                                                    <th>Motor</th>
                                                    <th>Warna</th>
                                                    <th>Nomor Rangka</th>
                                                    <th>Nomor Mesin</th>
                                                    <th>Harga Jual</th>
                                                    <th>Tanggal Terjual</th>
                                                @break

                                                @case('soldSpareParts')
                                                    <th>Spare Part</th>
                                                    <th>Quantity</th>
                                                    <th>Harga Jual</th>
                                                    <th>Tanggal Terjual</th>
                                                @break
                                            @endswitch
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reportData[$table] as $item)
                                            <tr>
                                                @switch($table)
                                                    @case('orderMotors')
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>{{ $item->motor['nama_motor'] ?? 'N/A' }}</td>
                                                        <td>{{ $item->warna['nama_warna'] ?? 'N/A' }}</td>
                                                        <td>{{ $item->nomor_rangka }}</td>
                                                        <td>{{ $item->nomor_mesin }}</td>
                                                        <td>{{ $item->jumlah }}</td>
                                                        <td>{{ number_format($item->harga_jual, 2) }}</td>
                                                        <td>{{ $item->created_at }}</td>
                                                    @break

                                                    @case('orderSpareParts')
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>{{ $item->sparePart->nama_spare_part ?? 'N/A' }}</td>
                                                        <td>{{ $item->jumlah }}</td>
                                                        <td>{{ number_format($item->harga_jual, 2) }}</td>
                                                        <td>{{ $item->created_at }}</td>
                                                    @break

                                                    @case('soldMotors')
                                                        <td>{{ $item->motor['nama_motor'] ?? 'N/A' }}</td>
                                                        <td>{{ $item->warna['nama_warna'] ?? 'N/A' }}</td>
                                                        <td>{{ $item->nomor_rangka }}</td>
                                                        <td>{{ $item->nomor_mesin }}</td>
                                                        <td>{{ number_format($item->harga_jual, 2) }}</td>
                                                        <td>{{ $item->tanggal_terjual }}</td>
                                                    @break

                                                    @case('soldSpareParts')
                                                        <td>{{ $item->sparePart->nama_spare_part ?? 'N/A' }}</td>
                                                        <td>{{ $item->jumlah }}</td>
                                                        <td>{{ number_format($item->harga_jual, 2) }}</td>
                                                        <td>{{ $item->tanggal_terjual }}</td>
                                                    @break
                                                @endswitch
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
