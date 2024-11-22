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
                                        @foreach ($soldMotors as $index => $motor)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $motor->motor->nama_motor }}</td>
                                                <td>{{ $motor->warna->nama_warna }}</td>
                                                <td>{{ $motor->nomor_rangka }}</td>
                                                <td>{{ $motor->nomor_mesin }}</td>
                                                <td>Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</td>
                                                <td>{{ $motor->tanggal_terjual }}</td>
                                            </tr>
                                        @endforeach
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
                                        @foreach ($soldSpareParts as $index => $sparePart)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $sparePart->sparePart->nama_spare_part }}</td>
                                                <td>{{ $sparePart->jumlah }}</td>
                                                <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                                <td>{{ $sparePart->tanggal_terjual }}</td>
                                            </tr>
                                        @endforeach
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