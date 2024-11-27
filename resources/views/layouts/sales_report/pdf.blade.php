<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1,
        h4 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>Sales Report for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h1>

    @foreach ($tables as $table)
        @if (isset($reportData[$table]) && count($reportData[$table]) > 0)
            <h2>{{ ucfirst($table) }}</h2>
            <table>
                <thead>
                    <tr>
                        @switch($table)
                            @case('orderMotors')
                                <th>User</th>
                                <th>Motor</th>
                                <th>Color</th>
                                <th>Frame Number</th>
                                <th>Engine Number</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Order Date</th>
                            @break

                            @case('orderSpareParts')
                                <th>User</th>
                                <th>Spare Part</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Order Date</th>
                            @break

                            @case('soldMotors')
                                <th>Motor</th>
                                <th>Color</th>
                                <th>Frame Number</th>
                                <th>Engine Number</th>
                                <th>Selling Price</th>
                                <th>Sold Date</th>
                            @break

                            @case('soldSpareParts')
                                <th>Spare Part</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Sold Date</th>
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
                                    <td>{{ $item->motor->nama_motor }}</td>
                                    <td>{{ $item->warna->nama_warna }}</td>
                                    <td>{{ $item->nomor_rangka }}</td>
                                    <td>{{ $item->nomor_mesin }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ number_format($item->harga_jual, 2) }}</td>
                                    <td>{{ $item->created_at }}</td>
                                @break

                                @case('orderSpareParts')
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->sparePart->nama_spare_part }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ number_format($item->harga_jual, 2) }}</td>
                                    <td>{{ $item->created_at }}</td>
                                @break

                                @case('soldMotors')
                                    <td>{{ $item->motor->nama_motor }}</td>
                                    <td>{{ $item->warna->nama_warna }}</td>
                                    <td>{{ $item->nomor_rangka }}</td>
                                    <td>{{ $item->nomor_mesin }}</td>
                                    <td>{{ number_format($item->harga_jual, 2) }}</td>
                                    <td>{{ $item->tanggal_terjual }}</td>
                                @break

                                @case('soldSpareParts')
                                    <td>{{ $item->sparePart->nama_spare_part }}</td>
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
</body>

</html>
