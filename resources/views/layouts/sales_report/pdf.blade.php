<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            /* Ukuran font dasar yang lebih kecil */
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px;
            /* Padding yang lebih kecil */
            text-align: left;
            white-space: nowrap;
            /* Mencegah text wrap */
            overflow: hidden;
            text-overflow: ellipsis;
            /* Menambahkan ellipsis jika text terlalu panjang */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h1 {
            font-size: 16px;
            margin-top: 20px;
        }

        h2 {
            font-size: 14px;
            margin-top: 15px;
        }

        .table-responsive {
            overflow-x: auto;
            /* Memungkinkan scroll horizontal jika diperlukan */
        }
    </style>
</head>

<body>
    <h1>Sales Report for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h1>

    @foreach ($tables as $table)
        @if (isset($reportData[$table]) && count($reportData[$table]) > 0)
            <h2>{{ ucfirst($table) }}</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            @switch($table)
                                @case('orderMotors')
                                    <th>User</th>
                                    <th>Motor</th>
                                    <th>Warna</th>
                                    <th>Nomor Rangka</th>
                                    <th>Nomor Mesin</th>
                                    <th>Qty</th>
                                    <th>Harga Jual</th>
                                    <th>Tgl Order</th>
                                @break

                                @case('orderSpareParts')
                                    <th>User</th>
                                    <th>Spare Part</th>
                                    <th>Qty</th>
                                    <th>Harga Jual</th>
                                    <th>Tgl Order</th>
                                @break

                                @case('soldMotors')
                                    <th>Motor</th>
                                    <th>Warna</th>
                                    <th>Nomor Rangka</th>
                                    <th>Nomor Mesin</th>
                                    <th>Harga Jual</th>
                                    <th>Tgl Terjual</th>
                                @break

                                @case('soldSpareParts')
                                    <th>Spare Part</th>
                                    <th>Qty</th>
                                    <th>Harga Jual</th>
                                    <th>Tgl Terjual</th>
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
                                        <td>{{ number_format($item->harga_jual, 0) }}</td>
                                        <td>{{ $item->created_at instanceof \Carbon\Carbon ? $item->created_at->format('d/m/Y') : $item->created_at }}
                                        </td>
                                    @break

                                    @case('orderSpareParts')
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->sparePart->nama_spare_part ?? 'N/A' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ number_format($item->harga_jual, 0) }}</td>
                                        <td>{{ $item->created_at instanceof \Carbon\Carbon ? $item->created_at->format('d/m/Y') : $item->created_at }}
                                        </td>
                                    @break

                                    @case('soldMotors')
                                        <td>{{ $item->motor['nama_motor'] ?? 'N/A' }}</td>
                                        <td>{{ $item->warna['nama_warna'] ?? 'N/A' }}</td>
                                        <td>{{ $item->nomor_rangka }}</td>
                                        <td>{{ $item->nomor_mesin }}</td>
                                        <td>{{ number_format($item->harga_jual, 0) }}</td>
                                        <td>{{ $item->tanggal_terjual instanceof \Carbon\Carbon ? $item->tanggal_terjual->format('d/m/Y') : $item->tanggal_terjual }}
                                        </td>
                                    @break

                                    @case('soldSpareParts')
                                        <td>{{ $item->sparePart->nama_spare_part ?? 'N/A' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ number_format($item->harga_jual, 0) }}</td>
                                        <td>{{ $item->tanggal_terjual instanceof \Carbon\Carbon ? $item->tanggal_terjual->format('d/m/Y') : $item->tanggal_terjual }}
                                        </td>
                                    @break
                                @endswitch
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</body>

</html>
