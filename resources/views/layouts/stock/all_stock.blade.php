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

        .modal-xl {
            max-width: 90%;
            width: 90%;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        @media (min-width: 576px) {
            .modal-dialog-centered {
                min-height: calc(100% - 3.5rem);
            }
        }

        #motorSearch,
        #sparePartSearch,
        #motorDetailSearch {
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
                <h1>All Stock Data</h1>
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
                            <!-- Motors Tab -->
                            <div class="tab-pane fade show active" id="motors" role="tabpanel">
                                <div class="mb-3">
                                    <input type="text" id="motorNameSearch" class="form-control"
                                        placeholder="Search Motor Name">
                                </div>

                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-striped motorTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Motor</th>
                                                <th>Total Unit</th>
                                                <th>Harga Beli (Sebelumnya - Terbaru)</th>
                                                <th>Harga Jual (Sebelumnya - Terbaru)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedMotors as $motorName => $motors)
                                                @php
                                                    $sortedMotors = $motors->sortBy('created_at');
                                                    $oldestMotor = $sortedMotors->first();
                                                    $newestMotor = $sortedMotors->last();
                                                @endphp
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $motorName }}</td>
                                                    <td>{{ $motors->count() }}</td>
                                                    <td>
                                                        @if ($oldestMotor->harga_beli != $newestMotor->harga_beli)
                                                            Rp {{ number_format($oldestMotor->harga_beli, 0, ',', '.') }} -
                                                            Rp {{ number_format($newestMotor->harga_beli, 0, ',', '.') }}
                                                        @else
                                                            Rp {{ number_format($newestMotor->harga_beli, 0, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($oldestMotor->harga_jual != $newestMotor->harga_jual)
                                                            Rp {{ number_format($oldestMotor->harga_jual, 0, ',', '.') }} -
                                                            Rp {{ number_format($newestMotor->harga_jual, 0, ',', '.') }}
                                                        @else
                                                            Rp {{ number_format($newestMotor->harga_jual, 0, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm detail-btn"
                                                            data-motor-name="{{ $motorName }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Spare Parts Tab -->
                            <div class="tab-pane fade" id="spare-parts" role="tabpanel">
                                <div class="mb-3">
                                    <input type="text" id="sparePartSearch" class="form-control"
                                        placeholder="Search Spare Part">
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-striped" id="sparePartsTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Spare Part Name</th>
                                                <th>Type</th>
                                                <th>Jumlah</th>
                                                <th>Harga Beli</th>
                                                <th>Harga Jual</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($spareParts as $index => $sparePart)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $sparePart->sparePart->nama_spare_part }}</td>
                                                    <td>{{ $sparePart->type }}</td>
                                                    <td>{{ $sparePart->jumlah }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_beli, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $sparePart->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Tidak ada spare part tersedia
                                                    </td>
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

    <!-- Modal -->
    <div class="modal fade" id="motorDetailModal" tabindex="-1" role="dialog" aria-labelledby="motorDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="motorDetailModalLabel">Detail Motor: <span id="motorNameTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="motorDetailSearch" class="form-control mb-3" placeholder="Search in details">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="motorDetailTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Motor</th>
                                    <th>Warna</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Nomor Rangka</th>
                                    <th>Nomor Mesin</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody id="motorDetailTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var motorTable, motorDetailTable, sparePartsTable;
            var activeTab = 'motors';

            function initMotorTable() {
                motorTable = $('.motorTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    searching: false,
                    order: [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                            targets: 0,
                            searchable: false,
                            orderable: false
                        },
                        {
                            targets: 5,
                            orderable: false
                        }
                    ],
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

                // Menambahkan nomor urut yang konsisten
                motorTable.on('order.dt search.dt', function() {
                    motorTable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            function initMotorDetailTable() {
                motorDetailTable = $('#motorDetailTable').DataTable({
                    responsive: true,
                    searching: false,
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ],
                    scrollY: '50vh',
                    scrollCollapse: true,
                    columnDefs: [{
                        targets: 0,
                        searchable: false,
                        orderable: false
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

                // Menambahkan nomor urut yang konsisten
                motorDetailTable.on('order.dt search.dt', function() {
                    motorDetailTable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                });
            }

            function initSparePartsTable() {
                sparePartsTable = $('#sparePartsTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    searching: false,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: 0,
                        searchable: false,
                        orderable: false
                    }],
                    language: {
                        paginate: {
                            next: '<i class="fas fa-chevron-right"></i>',
                            previous: '<i class="fas fa-chevron-left"></i>'
                        },
                        emptyTable: "Tidak ada data spare part tersedia"
                    },
                    drawCallback: function() {
                        $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                    }
                });

                // Menambahkan nomor urut yang konsisten
                sparePartsTable.on('order.dt search.dt', function() {
                    sparePartsTable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            initMotorTable();
            initMotorDetailTable();
            initSparePartsTable();

            // Menangani klik tombol detail
            $('.motorTable').on('click', '.detail-btn', function() {
                var motorName = $(this).data('motor-name');
                showMotorDetails(motorName);
            });

            function showMotorDetails(motorName) {
                $('#motorNameTitle').text(motorName);

                motorDetailTable.clear();

                var filteredMotors = {!! json_encode($groupedMotors) !!}[motorName];

                filteredMotors.forEach(function(motor, index) {
                    motorDetailTable.row.add([
                        index + 1,
                        motor.motor.nama_motor,
                        motor.warna.nama_warna || 'N/A',
                        'Rp ' + parseFloat(motor.harga_jual).toLocaleString('id-ID'),
                        'Rp ' + parseFloat(motor.harga_beli).toLocaleString('id-ID'),
                        motor.nomor_rangka || 'N/A',
                        motor.nomor_mesin || 'N/A',
                        new Date(motor.created_at).toLocaleString('id-ID')
                    ]);
                });

                motorDetailTable.draw();
                $('#motorDetailModal').modal('show');
            }

            // Pencarian di dalam modal detail
            $('#motorDetailSearch').on('keyup', function() {
                motorDetailTable.search(this.value).draw();
            });

            // Tab Change Event
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                if (e.target.id === 'motors-tab') {
                    activeTab = 'motors';
                    motorTable.columns.adjust().responsive.recalc();
                } else if (e.target.id === 'spare-parts-tab') {
                    activeTab = 'spare-parts';
                    sparePartsTable.columns.adjust().responsive.recalc();
                }
            });

            // Motor Name Search Event
            $('#motorNameSearch').on('keyup', function() {
                if (activeTab === 'motors') {
                    motorTable.search(this.value).draw();
                }
            });

            // Spare Parts Search Event
            $('#sparePartSearch').on('keyup', function() {
                if (activeTab === 'spare-parts') {
                    sparePartsTable.search(this.value).draw();
                }
            });

            // Adjust column widths on window resize
            $(window).on('resize', function() {
                motorTable.columns.adjust().responsive.recalc();
                sparePartsTable.columns.adjust().responsive.recalc();
                if ($('#motorDetailModal').hasClass('show')) {
                    motorDetailTable.columns.adjust().responsive.recalc();
                }
            });

            // Adjust column widths when modal is shown
            $('#motorDetailModal').on('shown.bs.modal', function() {
                motorDetailTable.columns.adjust().responsive.recalc();

                // Menyesuaikan tinggi scrolling
                var modalHeight = $('#motorDetailModal .modal-content').height();
                var headerHeight = $('#motorDetailModal .modal-header').outerHeight();
                var searchHeight = $('#motorDetailSearch').outerHeight();
                var paginationHeight = $('#motorDetailTable_wrapper .dataTables_paginate').outerHeight();
                var scrollBodyHeight = modalHeight - headerHeight - searchHeight - paginationHeight -
                    100; // 100 untuk padding dan margin

                $('#motorDetailTable_wrapper .dataTables_scrollBody').css('max-height', scrollBodyHeight +
                    'px');
                motorDetailTable.columns.adjust().draw();
            });
        });
    </script>
@endpush
