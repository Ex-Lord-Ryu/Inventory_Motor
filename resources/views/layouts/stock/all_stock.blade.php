@extends('layouts.app')

@section('title', 'All Stock Data')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .main-content {
            padding: 20px;
        }

        .section-header {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .nav-tabs .nav-link {
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }

        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
            border-bottom-color: transparent;
        }

        .table {
            background-color: white;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .03);
        }

        .table th {
            background-color: #f8f9fa;
        }

        .dataTables_filter {
            margin-bottom: 15px;
        }

        .dataTables_filter input {
            border-radius: 20px;
            padding-left: 30px;
        }

        .dataTables_filter label::before {
            content: "\f002";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 10px;
            top: 6px;
            color: #aaa;
        }

        #motorSearch,
        #sparePartSearch {
            border-radius: 20px;
            padding-left: 30px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>');
            background-repeat: no-repeat;
            background-position: 10px center;
            background-size: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1></i>All Stock Data</h1>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="motors-tab" data-toggle="tab" href="#motors" role="tab">
                                    <i class="fas fa-motorcycle mr-2"></i>Motors
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="spare-parts-tab" data-toggle="tab" href="#spare-parts"
                                    role="tab">
                                    <i class="fas fa-cogs mr-2"></i>Spare Parts
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="stockTabContent">
                            <div class="tab-pane fade show active" id="motors" role="tabpanel">
                                @foreach ($groupedMotors as $motorName => $motors)
                                    <h4 class="mt-4"><i class="fas fa-tag mr-2"></i>{{ $motorName }}</h4>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered table-striped motorTable">
                                            <div class="mb-3">
                                                <input type="text" id="motorSearch" class="form-control"
                                                    placeholder="Search Motor">
                                            </div>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Warna</th>
                                                    <th>Type</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga Beli</th>
                                                    <th>Harga Jual</th>
                                                    <th>Nomor Rangka</th>
                                                    <th>Nomor Mesin</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($motors as $motor)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $motor->warna->nama_warna ?? 'N/A' }}</td>
                                                        <td>{{ $motor->type }}</td>
                                                        <td>{{ $motor->jumlah }}</td>
                                                        <td>Rp {{ number_format($motor->harga_beli, 0, ',', '.') }}</td>
                                                        <td>Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</td>
                                                        <td>{{ $motor->nomor_rangka ?? 'N/A' }}</td>
                                                        <td>{{ $motor->nomor_mesin ?? 'N/A' }}</td>
                                                        <td>{{ $motor->created_at->format('Y-m-d H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-pane fade" id="spare-parts" role="tabpanel">
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-striped" id="sparePartsTable">
                                        <div class="mb-3">
                                            <input type="text" id="sparePartSearch" class="form-control"
                                                placeholder="Search Spare Part">
                                        </div>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Spare Part Name</th>
                                                <th>Type</th>
                                                <th>Jumlah</th>
                                                <th>Harga Beli</th>
                                                <th>Harga Jual</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($spareParts as $sparePart)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $sparePart->sparePart->nama_spare_part }}</td>
                                                    <td>{{ $sparePart->type }}</td>
                                                    <td>{{ $sparePart->jumlah }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_beli, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $sparePart->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Motor Table
            var motorTable = $('.motorTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }],
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                }
            });

            // Custom search for motor table
            $('#motorSearch').on('keyup', function() {
                motorTable.search(this.value).draw();
            });

            // Spare Parts Table
            var sparePartsTable = $('#sparePartsTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }],
                language: {
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                }
            });

            // Custom search for spare parts table
            $('#sparePartSearch').on('keyup', function() {
                sparePartsTable.search(this.value).draw();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });

            // Add smooth scrolling to all links
            $("a").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function() {
                        window.location.hash = hash;
                    });
                }
            });
        });
    </script>
@endpush
