@extends('layouts.app')

@section('title', 'Sold Items')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Sold Items</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Filter and Search</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('stock.sold-items') }}" method="GET">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="month">Month</label>
                                            <select name="month" id="month" class="form-control">
                                                @foreach ($months as $key => $monthName)
                                                    <option value="{{ $key }}"
                                                        {{ $month == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control">
                                                @foreach ($years as $yearOption)
                                                    <option value="{{ $yearOption }}"
                                                        {{ $year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="search">Search</label>
                                            <input type="text" name="search" id="search" class="form-control"
                                                placeholder="Search..." value="{{ request('search') }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Sold Motors</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Motor Name</th>
                                                <th>Warna</th>
                                                <th>Nomor Rangka</th>
                                                <th>Nomor Mesin</th>
                                                <th>Harga Jual</th>
                                                <th>Tanggal Terjual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($soldMotors as $motor)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $motor->motor->nama_motor }}</td>
                                                    <td>{{ $motor->warna->nama_warna }}</td>
                                                    <td>{{ $motor->nomor_rangka }}</td>
                                                    <td>{{ $motor->nomor_mesin }}</td>
                                                    <td>Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $motor->tanggal_terjual }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Tidak ada data motor terjual</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Sold Spare Parts</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Spare Part Name</th>
                                                <th>Jumlah</th>
                                                <th>Harga Jual</th>
                                                <th>Tanggal Terjual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($soldSpareParts as $sparePart)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $sparePart->sparePart->nama_spare_part }}</td>
                                                    <td>{{ $sparePart->jumlah }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $sparePart->tanggal_terjual }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada data spare part terjual</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // You can add any additional JavaScript here if needed
            // For example, you might want to add datepicker for date inputs
            // or add more interactivity to the search and filter functionality
        });
    </script>
@endpush
