@extends('layouts.app')

@section('title', 'Order Motor')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            display: flex;
            flex: 1;
        }

        .main-sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order Motor</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Place New Order</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>User:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
                        <form id="orderForm" action="{{ route('order_motor.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="motor_id">Motor</label>
                                <select name="motor_id" id="motor_id" class="form-control" required>
                                    <option value="">Select a motor</option>
                                    @foreach ($availableMotors as $motorData)
                                        <option value="{{ $motorData['motor']->id }}">
                                            {{ $motorData['motor']->nama_motor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="warna_id">Color</label>
                                <select name="warna_id" id="warna_id" class="form-control" required>
                                    <option value="">Select a color</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Quantity</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1"
                                    value="1" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Recent Orders</h4>
                        <div class="card-header-form">
                            <form id="filterForm" method="GET" action="{{ route('order_motor.index') }}">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label for="monthFilter" class="form-label">Month</label>
                                        <select name="month" class="form-control" id="monthFilter">
                                            <option value="">All Months</option>
                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ $month }}" 
                                                    {{ (request('month', date('n')) == $month && !request('filter')) ? 'selected' : 
                                                       (request('month') == $month && request('filter') ? 'selected' : '') }}>
                                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="yearFilter" class="form-label">Year</label>
                                        <select name="year" class="form-control" id="yearFilter">
                                            <option value="">All Years</option>
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = 2020;
                                            @endphp
                                            @foreach(range($currentYear, $startYear) as $year)
                                                <option value="{{ $year }}" 
                                                    {{ (request('year', date('Y')) == $year && !request('filter')) ? 'selected' : 
                                                       (request('year') == $year && request('filter') ? 'selected' : '') }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2 d-flex align-items-end">
                                        <input type="hidden" name="filter" value="true">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <a href="{{ route('order_motor.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Motor</th>
                                        <th>Color</th>
                                        <th>Quantity</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalOrders = count($recentOrders);
                                    @endphp
                                    @foreach ($recentOrders as $index => $order)
                                        <tr>
                                            <td>{{ $totalOrders - $index }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->motor->nama_motor }}</td>
                                            <td>{{ $order->warna->nama_warna }}</td>
                                            <td>{{ $order->jumlah }}</td>
                                            <td>{{ $order->tanggal_terjual }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#orderModal{{ $order->id }}"
                                                    data-order-number="{{ $totalOrders - $index }}">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @foreach ($recentOrders as $index => $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                            <i class="fas fa-clipboard-list me-2"></i>Order Detail #<span class="order-number"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="printableArea{{ $order->id }}">
                            <h2>Order Detail #<span class="order-number"></span></h2>
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">User Information</th>
                                    <th colspan="2">Order Information</th>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $order->tanggal_terjual }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>{{ $order->user->role }}</td>
                                    <td><strong>Status:</strong></td>
                                    <td>Completed</td>
                                </tr>
                                <tr>
                                    <th colspan="4">Product Details</th>
                                </tr>
                                <tr>
                                    <td><strong>Motor:</strong></td>
                                    <td>{{ $order->motor->nama_motor }}</td>
                                    <td><strong>Color:</strong></td>
                                    <td>{{ $order->warna->nama_warna }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity:</strong></td>
                                    <td>{{ $order->jumlah }}</td>
                                    <td><strong>Harga Motor:</strong></td>
                                    <td>Rp {{ number_format($order->harga_jual, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Harga:</strong></td>
                                    <td>Rp {{ number_format($order->harga_jual * $order->jumlah, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Rangka:</strong></td>
                                    <td>{{ $order->nomor_rangka }}</td>
                                    <td><strong>Nomor Mesin:</strong></td>
                                    <td colspan="3">{{ $order->nomor_mesin }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printOrder({{ $order->id }})">Print
                            Order</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($(".main-sidebar").length) {
                $(".main-sidebar").niceScroll({
                    cursorcolor: "#6777ef",
                    cursorborder: "0px",
                    cursoropacitymin: 0.1,
                    cursoropacitymax: 0.8,
                    zindex: 999999,
                });
            }

            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const mainSidebar = document.querySelector('.main-sidebar');
            const mainContent = document.querySelector('.main-content');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    mainSidebar.classList.toggle('active');
                    mainContent.classList.toggle('active');
                });
            }

            initializeModals();

            const motorSelect = document.getElementById('motor_id');
            const warnaSelect = document.getElementById('warna_id');
            const availableMotors = @json($availableMotors);

            motorSelect.addEventListener('change', function() {
                const selectedMotorId = this.value;
                const warnaOptions = warnaSelect.options;

                for (let i = 0; i < warnaOptions.length; i++) {
                    warnaOptions[i].disabled = true;
                }

                if (selectedMotorId) {
                    const selectedMotor = availableMotors.find(motor => motor.motor.id == selectedMotorId);
                    if (selectedMotor && selectedMotor.warnas) {
                        selectedMotor.warnas.forEach(warna => {
                            const option = warnaSelect.querySelector(`option[value="${warna.id}"]`);
                            if (option) {
                                option.disabled = false;
                            }
                        });
                    }
                }

                // Reset warna selection
                warnaSelect.value = "";
            });

            // Add filter change handlers
            // $('#monthFilter, #yearFilter').on('change', function() {
            //     $('#filterForm').submit();
            // });

            // Add this new code for form submission
            $('#orderForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Menyimpan Data',
                    text: 'Apakah Anda yakin ingin menyimpan order ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Order berhasil disimpan.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                console.log('Error response:', xhr.responseJSON);

                                let errorMessage =
                                    'Terjadi kesalahan saat menyimpan order.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                console.log('Error message:', errorMessage);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });

        // Add this new code to update the order number in the modal
        $('.modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var orderNumber = button.data('order-number');
            var modal = $(this);
            modal.find('.order-number').text(orderNumber);
        });

        function printOrder(orderId) {
            const printWindow = window.open('', '_blank');
            const printContent = document.getElementById(`printableArea${orderId}`).innerHTML;

            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Order</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; }
                            h2 { text-align: center; }
                            .text-right { text-align: right; }
                            @media print {
                                body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                            }
                        </style>
                    </head>
                    <body>${printContent}</body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            printWindow.onload = function() {
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                }
            }
        }

        $(document).ready(function() {
            const motorSelect = document.getElementById('motor_id');
            const warnaSelect = document.getElementById('warna_id');
            const availableMotors = @json($availableMotors);

            motorSelect.addEventListener('change', function() {
                const selectedMotorId = this.value;

                // Clear existing options
                warnaSelect.innerHTML = '<option value="">Select a color</option>';

                if (selectedMotorId) {
                    const selectedMotor = availableMotors.find(motor => motor.motor.id == selectedMotorId);
                    if (selectedMotor && selectedMotor.warnas) {
                        selectedMotor.warnas.forEach(warna => {
                            const option = document.createElement('option');
                            option.value = warna.id;
                            option.textContent = `${warna.nama} (Stock: ${warna.stock})`;
                            warnaSelect.appendChild(option);
                        });
                    }
                }
            });
        });

        function initializeModals() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                new bootstrap.Modal(modal);
            });
        }
    </script>
@endpush
