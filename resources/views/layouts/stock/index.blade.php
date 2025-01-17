@extends('layouts.app')

@section('title', 'Stocks')

@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            line-height: normal;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border: none;
            outline: none;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .table-bordered th {
            width: 30%;
            background-color: #f8f9fa;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            table-layout: auto;
        }

        .table th,
        .table td {
            white-space: nowrap;
            padding: 0.5rem;
        }

        @media (max-width: 768px) {
            .table th,
            .table td {
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Stocks</h1>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('stock.index') }}" method="GET" class="row">
                                    <div class="form-group col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Search..."
                                            value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary btn-block" type="submit">Search</button>
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
                                <h4>Motors</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Motor Name</th>
                                                <th>Warna</th>
                                                <th>Jumlah</th>
                                                <th>Harga Jual</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($motors as $index => $motor)
                                                <tr>
                                                    <td>{{ ($motors->currentPage() - 1) * $motors->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $motor->motor->nama_motor }}</td>
                                                    <td>{{ $motor->warna->nama_warna ?? 'N/A' }}</td>
                                                    <td>{{ $motor->jumlah }}</td>
                                                    <td>Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $motor->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#motorModal{{ $motor->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <a href="{{ route('stock.edit-pricing', ['id' => $motor->id, 'type' => 'motor']) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Update Harga
                                                        </a>
                                                        <a href="{{ route('stock.input-motor') }}"
                                                            class="btn btn-primary btn-sm">Input Data</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($motors->hasPages())
                                    <div class="card-footer">
                                        <nav class="d-inline-block">
                                            {{ $motors->appends(request()->query())->links('pagination::bootstrap-4') }}
                                        </nav>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Spare Parts</h4>
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
                                                <th>Tanggal Masuk</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($spareParts as $index => $sparePart)
                                                <tr>
                                                    <td>{{ ($spareParts->currentPage() - 1) * $spareParts->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $sparePart->sparePart->nama_spare_part }}</td>
                                                    <td>{{ $sparePart->jumlah }}</td>
                                                    <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                                    <td>{{ $sparePart->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#sparePartModal{{ $sparePart->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <a href="{{ route('stock.edit-pricing', ['id' => $sparePart->id, 'type' => 'spare_part']) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Update Harga
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($spareParts->hasPages())
                                    <div class="card-footer">
                                        <nav class="d-inline-block">
                                            {{ $spareParts->appends(request()->query())->links('pagination::bootstrap-4') }}
                                        </nav>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result Summary Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted">
                                    Showing {{ $motors->total() }} motors and {{ $spareParts->total() }} spare parts
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Motor Modals -->
    @foreach ($motors as $motor)
        <div class="modal fade" id="motorModal{{ $motor->id }}" tabindex="-1" role="dialog"
            aria-labelledby="motorModalLabel{{ $motor->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="motorModalLabel{{ $motor->id }}">Motor Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Order</th>
                                        <td>{{ $motor->order }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah</th>
                                        <td>{{ $motor->jumlah }}</td>
                                    </tr>
                                    <tr>
                                        <th>Warna</th>
                                        <td>{{ $motor->warna->nama_warna ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Harga Beli</th>
                                        <td>Rp {{ number_format($motor->harga_beli, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Jual</th>
                                        <td>Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Masuk</th>
                                        <td>{{ $motor->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diupdate</th>
                                        <td>{{ $motor->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Spare Part Modals -->
    @foreach ($spareParts as $sparePart)
        <div class="modal fade" id="sparePartModal{{ $sparePart->id }}" tabindex="-1" role="dialog"
            aria-labelledby="sparePartModalLabel{{ $sparePart->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sparePartModalLabel{{ $sparePart->id }}">Spare Part Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Order</th>
                                        <td>{{ $sparePart->order }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah</th>
                                        <td>{{ $sparePart->jumlah }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Beli</th>
                                        <td>Rp {{ number_format($sparePart->harga_beli, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Harga Jual</th>
                                        <td>Rp {{ number_format($sparePart->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Masuk</th>
                                        <td>{{ $sparePart->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diupdate</th>
                                        <td>{{ $sparePart->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            // Updated function to adjust table height
            function adjustTableHeight() {
                $('.table-responsive').each(function() {
                    var $tableContainer = $(this);
                    var $table = $tableContainer.find('table');
                    
                    // Get window height
                    var windowHeight = $(window).height();
                    
                    // Calculate maximum height (70% of window height)
                    var maxHeight = Math.floor(windowHeight * 1);
                    
                    // Get actual table height
                    var tableHeight = $table.height();
                    
                    // Set container height to either table height or max height, whichever is smaller
                    var finalHeight = Math.min(tableHeight + 20, maxHeight); // Added 20px for padding
                    
                    $tableContainer.css({
                        'max-height': finalHeight + 'px',
                        'height': 'auto'
                    });
                });
            }

            // Call the function initially and on window resize
            adjustTableHeight();
            $(window).resize(adjustTableHeight);

            // Event listener for pagination links
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var cardSection = $(this).closest('.card');
                var target = cardSection.find('.table-responsive');
                var paginationContainer = cardSection.find('.card-footer');
            
                // Add loading indicator
                target.addClass('loading').append('<div class="text-center mt-3"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
            
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Find the corresponding card in the response
                        var responseCard = $(response).find('.card').filter(function() {
                            return $(this).find('h4').text() === cardSection.find('h4').text();
                        });
                        
                        // Update only the table content and pagination for the specific section
                        target.html(responseCard.find('.table-responsive').html());
                        paginationContainer.html(responseCard.find('.card-footer').html());
                        
                        // Remove loading indicator
                        target.removeClass('loading');
                        
                        // Readjust table height
                        adjustTableHeight();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading data:", error);
                        target.removeClass('loading');
                        target.find('.text-center').remove();
                        alert('Error loading data. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush