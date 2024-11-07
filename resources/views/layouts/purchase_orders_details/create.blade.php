@extends('layouts.app')

@section('title', 'Create Purchase Order Detail')

@section('content')
    <style>
        .input-group-text {
            min-width: 40px;
            justify-content: center;
        }

        .table th {
            vertical-align: middle !important;
            background-color: #f4f6f9;
        }

        .form-control:disabled {
            background-color: #e9ecef !important;
        }

        .input-group .form-control {
            text-align: center;
        }

        .quantity-input {
            width: 60px !important;
            text-align: center;
        }

        .price-input {
            text-align: right !important;
        }

        .total-column {
            font-weight: bold;
            text-align: right;
        }

        .modal-lg {
            max-width: 1000px;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        .selected-items {
            margin-bottom: 20px;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Purchase Order Details</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('purchase_orders_details.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="purchase_order_id">Purchase Order ID</label>
                                <select name="purchase_order_id" id="purchase_order_id" class="form-control" required>
                                    <option value="">Select Purchase Order</option>
                                    @foreach ($purchaseOrders as $order)
                                        <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->invoice }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase_order_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Motor and Spare Part Selection with Modals -->
                            <div class="form-group">
                                <label for="motor_id">Motors</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="motor_input" class="form-control" placeholder="Select Motor"
                                        readonly required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#motorModal">Pilih Motor</button>
                                    </div>
                                </div>
                                <div id="motorSelection"></div>
                            </div>

                            <div class="form-group">
                                <label for="spare_part_id">Spare Parts</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="spare_part_input" class="form-control"
                                        placeholder="Select Spare Part" readonly required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#sparePartModal">Pilih Spare Part</button>
                                    </div>
                                </div>
                                <div id="sparePartSelection"></div>
                            </div>

                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="total_harga" id="total_harga"
                                        class="form-control text-right" placeholder="0" readonly>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('purchase_orders_details.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Motor and Spare Part Modals -->

    <!-- Motor Modal -->
    <div class="modal fade" id="motorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Motor</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 20%">Name</th>
                                    <th class="text-center" style="width: 20%">Quantity</th>
                                    <th class="text-center" style="width: 25%">Price</th>
                                    <th class="text-center" style="width: 15%">Total</th>
                                    <th class="text-center" style="width: 15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($motors as $index => $motor)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $motor->nama_motor }}</td>
                                        <td><input type="number" class="form-control motor-quantity quantity-input"
                                                value="1" min="1"></td>
                                        <td><input type="text" class="form-control motor-price price-input"
                                                placeholder="0" data-type="currency"></td>
                                        <td class="total-column motor-total">Rp 0</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary add-motor" data-id="{{ $motor->id }}"
                                                data-name="{{ $motor->nama_motor }}">
                                                <i class="fas fa-check"></i> Add
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
    </div>

    <!-- Spare Part Modal -->
    <div class="modal fade" id="sparePartModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Spare Part</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 20%">Name</th>
                                    <th class="text-center" style="width: 20%">Quantity</th>
                                    <th class="text-center" style="width: 25%">Price</th>
                                    <th class="text-center" style="width: 15%">Total</th>
                                    <th class="text-center" style="width: 15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spareParts as $index => $sparePart)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $sparePart->nama_spare_part }}</td>
                                        <td><input type="number" class="form-control spare-quantity quantity-input"
                                                value="1" min="1"></td>
                                        <td><input type="text" class="form-control spare-price price-input"
                                                placeholder="0" data-type="currency"></td>
                                        <td class="total-column spare-total">Rp 0</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary add-spare-part" data-id="{{ $sparePart->id }}"
                                                data-name="{{ $sparePart->nama_spare_part }}">
                                                <i class="fas fa-check"></i> Add
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let selectedMotors = new Map();
            let selectedSpareParts = new Map();
            let isSubmitting = false; // Variable to lock form submission

            function formatNumber(n) {
                return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            function parseNumber(str) {
                return parseInt(str.replace(/,/g, '')) || 0;
            }

            function updateTotalPrice() {
                let total = 0;
                selectedMotors.forEach(item => {
                    total += item.quantity * item.price;
                });
                selectedSpareParts.forEach(item => {
                    total += item.quantity * item.price;
                });
                $('#total_harga').val(formatNumber(total));
            }

            $(document).on('click', '.add-motor', function() {
                const row = $(this).closest('tr');
                const id = $(this).data('id');
                const name = $(this).data('name');
                const quantity = parseInt(row.find('.motor-quantity').val()) || 1;
                const price = parseNumber(row.find('.motor-price').val());

                if (price <= 0) {
                    alert('Please enter a valid price for the motor');
                    return;
                }

                if (selectedMotors.has(id)) {
                    let current = selectedMotors.get(id);
                    current.quantity += quantity;
                    selectedMotors.set(id, current);
                } else {
                    selectedMotors.set(id, {
                        id: id,
                        name: name,
                        quantity: quantity,
                        price: price
                    });
                }

                updateMotorSelection();
                $('#motorModal').modal('hide');
            });

            $(document).on('click', '.add-spare-part', function() {
                const row = $(this).closest('tr');
                const id = $(this).data('id');
                const name = $(this).data('name');
                const quantity = parseInt(row.find('.spare-quantity').val()) || 1;
                const price = parseNumber(row.find('.spare-price').val());

                if (price <= 0) {
                    alert('Please enter a valid price for the spare part');
                    return;
                }

                if (selectedSpareParts.has(id)) {
                    let current = selectedSpareParts.get(id);
                    current.quantity += quantity;
                    selectedSpareParts.set(id, current);
                } else {
                    selectedSpareParts.set(id, {
                        id: id,
                        name: name,
                        quantity: quantity,
                        price: price
                    });
                }

                updateSparePartSelection();
                $('#sparePartModal').modal('hide');
            });

            function updateMotorSelection() {
                const container = $('#motorSelection');
                container.empty();
                selectedMotors.forEach((item, id) => {
                    container.append(createSelectedItemHtml('motor', item));
                });
                updateTotalPrice();
            }

            function updateSparePartSelection() {
                const container = $('#sparePartSelection');
                container.empty();
                selectedSpareParts.forEach((item, id) => {
                    container.append(createSelectedItemHtml('spare-part', item));
                });
                updateTotalPrice();
            }

            function createSelectedItemHtml(type, item) {
                return `
                <div class="selected-item ${type}-item row mb-2">
                    <input type="hidden" name="${type}_ids[]" value="${item.id}">
                    <input type="hidden" name="${type}_quantities[]" value="${item.quantity}">
                    <input type="hidden" name="${type}_prices[]" value="${item.price}">
                    <div class="col-md-5">${item.name}</div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" value="${item.quantity}" readonly>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" value="${formatNumber(item.price)}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-${type}" data-id="${item.id}">Remove</button>
                    </div>
                </div>`;
            }

            $(document).on('click', '.remove-motor', function() {
                const id = $(this).data('id');
                selectedMotors.delete(id);
                updateMotorSelection();
            });

            $(document).on('click', '.remove-spare-part', function() {
                const id = $(this).data('id');
                selectedSpareParts.delete(id);
                updateSparePartSelection();
            });

            $('form').off('submit').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) return; // Prevent double submission

                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);
                isSubmitting = true;

                const poId = $('#purchase_order_id').val();
                if (!poId) {
                    alert('Please select a Purchase Order');
                    submitButton.prop('disabled', false);
                    isSubmitting = false;
                    return;
                }

                if (selectedMotors.size === 0 && selectedSpareParts.size === 0) {
                    alert('Please select at least one motor or spare part');
                    submitButton.prop('disabled', false);
                    isSubmitting = false;
                    return;
                }

                const formData = new FormData();
                formData.append('purchase_order_id', poId);
                
                selectedMotors.forEach((item, id) => {
                    formData.append('motor_ids[]', id);
                    formData.append('motor_quantities[]', item.quantity);
                    formData.append('motor_prices[]', item.price);
                });

                selectedSpareParts.forEach((item, id) => {
                    formData.append('spare_part_ids[]', id);
                    formData.append('spare_part_quantities[]', item.quantity);
                    formData.append('spare_part_prices[]', item.price);
                });

                formData.append('total_harga', parseNumber($('#total_harga').val()));

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data berhasil disimpan!');
                            window.location.href = response.redirect ||
                                '/purchase_orders_details';
                        } else {
                            alert(response.message || 'Error saving purchase order details');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        alert(response?.message ||
                            'Error saving purchase order details. Please try again.');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false);
                        isSubmitting = false;
                    }
                });
            });

            $(document).on('input', '.price-input', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(formatNumber(value));
            });
        });
    </script>
@endpush
