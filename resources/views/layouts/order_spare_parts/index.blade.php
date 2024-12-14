@extends('layouts.app')

@section('title', 'Order Spare Parts')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        .modal-xl {
            max-width: 90% !important;
            width: 90% !important;
        }

        .modal-content {
            min-height: 80vh;
        }

        .color-text {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.8);
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea,
            #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order Spare Parts</h1>
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
                        <form id ="orderForm" action="{{ route('order_spare_parts.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="spare_part_id">Spare Part</label>
                                <select name="spare_part_id" id="spare_part_id" class="form-control" required>
                                    <option value="">Select a spare part</option>
                                    @foreach ($availableSpareParts as $sparePartData)
                                        <option value="{{ $sparePartData['spare_part']->id }}">
                                            {{ $sparePartData['spare_part']->nama_spare_part }} (Stock:
                                            {{ $sparePartData['stock'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Quantity</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Recent Orders</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Spare Part</th>
                                        <th>Quantity</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrders as $index => $order)
                                        <tr>
                                            <td>{{ $recentOrders->count() - $index }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->sparePart->nama_spare_part }}</td>
                                            <td>{{ $order->jumlah }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#orderModal{{ $recentOrders->count() - $index }}">
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
    <div class="modal fade" id="orderModal{{ $recentOrders->count() - $index }}" tabindex="-1"
        aria-labelledby="orderModalLabel{{ $recentOrders->count() - $index }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="orderModalLabel{{ $recentOrders->count() - $index }}">
                        <i class="fas fa-clipboard-list me-2"></i>Order Detail #{{ $recentOrders->count() - $index }}
                    </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="printableArea{{ $order->id }}">
                            <h2>Order Detail #{{ $recentOrders->count() - $index}}</h2>
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">User Information</th>
                                    <th colspan="2">Order Information</th>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
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
                                    <td><strong>Spare Part:</strong></td>
                                    <td colspan="3">{{ $order->sparePart->nama_spare_part }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity:</strong></td>
                                    <td>{{ $order->jumlah }}</td>
                                    <td><strong>Price per Unit:</strong></td>
                                    <td>Rp {{ number_format($order->harga_jual, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Price:</strong></td>
                                    <td colspan="3">
                                        Rp {{ number_format($order->harga_jual * $order->jumlah, 2, ',', '.') }}
                                    </td>
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

            initializeModals();

            const sparePartSelect = document.getElementById('spare_part_id');
            const quantityInput = document.getElementById('jumlah');
            const availableSpareParts = @json($availableSpareParts);

            sparePartSelect.addEventListener('change', function() {
                const selectedSparePartId = this.value;
                const selectedSparePart = availableSpareParts.find(sp => sp.spare_part.id ==
                    selectedSparePartId);

                if (selectedSparePart) {
                    quantityInput.max = selectedSparePart.stock;
                    quantityInput.value = "1";
                } else {
                    quantityInput.removeAttribute('max');
                    quantityInput.value = "";
                }
            });

            quantityInput.addEventListener('input', function() {
                const selectedSparePartId = sparePartSelect.value;
                const selectedSparePart = availableSpareParts.find(sp => sp.spare_part.id ==
                    selectedSparePartId);

                if (selectedSparePart && this.value > selectedSparePart.stock) {
                    this.value = selectedSparePart.stock;
                }
            });
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

        function initializeModals() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                new bootstrap.Modal(modal);
            });
        }

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

                            let errorMessage = 'Terjadi kesalahan saat menyimpan order.';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            console.log('Error message:', errorMessage);

                            if (errorMessage ===
                                'Selling price is not set for this spare part.') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Harga Jual Belum Diatur',
                                    text: 'Harga jual untuk spare part ini belum diatur. Silakan hubungi admin untuk mengatur harga jual.',
                                    confirmButtonText: 'Mengerti'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
