@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Stock Details</h1>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $stock->id }}</td>
        </tr>
        <tr>
            <th>Type</th>
            <td>{{ $stock->type }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $stock->jumlah }}</td>
        </tr>
        <tr>
            <th>Harga Beli</th>
            <td>{{ $stock->harga_beli }}</td>
        </tr>
        <tr>
            <th>Harga Jual</th>
            <td>{{ $stock->harga_jual }}</td>
        </tr>
        <tr>
            <th>Harga Jual Diskon</th>
            <td>{{ $stock->harga_jual_diskon }}</td>
        </tr>
        <tr>
            <th>Nomor Rangka</th>
            <td>{{ $stock->nomor_rangka }}</td>
        </tr>
        <tr>
            <th>Nomor Mesin</th>
            <td>{{ $stock->nomor_mesin }}</td>
        </tr>
    </table>
    <a href="{{ route('stock.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection