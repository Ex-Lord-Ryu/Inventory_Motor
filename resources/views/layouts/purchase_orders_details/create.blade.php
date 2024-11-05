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
        }

        .form-control:disabled {
            background-color: #e9ecef !important;
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
                                <label for="po_id">Purchase Order ID</label>
                                <select name="po_id" id="po_id" class="form-control" required>
                                    <option value="">Select Purchase Order</option>
                                    @foreach ($purchaseOrders as $order)
                                        <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->invoice }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('po_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="invoice">Invoice</label>
                                <input type="text" name="invoice" id="invoice" class="form-control" required>
                                @error('invoice')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="motor_id">Motor</label>
                                <div class="input-group">
                                    <input type="text" id="motor_input" class="form-control" placeholder="Select Motor"
                                        readonly required>
                                    <input type="hidden" name="motor_id" id="motor_id">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#motorModal">Select Motor</button>
                                </div>
                                @error('motor_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="spare_part_id">Spare Part</label>
                                <div class="input-group">
                                    <input type="text" id="spare_part_input" class="form-control"
                                        placeholder="Select Spare Part" readonly required>
                                    <input type="hidden" name="spare_part_id" id="spare_part_id">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#sparePartModal">Select Spare Part</button>
                                </div>
                                @error('spare_part_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <input type="text" name="total_harga" id="total_harga" class="form-control"
                                    placeholder="Total Price" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('purchase_orders_details.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Motor Modal -->
    <div class="modal fade" id="motorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Motor</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 25%">Name</th>
                                    <th class="text-center" style="width: 20%">Jumlah</th>
                                    <th class="text-center" style="width: 25%">Harga</th>
                                    <th class="text-center" style="width: 25%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($motors as $index => $motor)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $motor->name }}</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-secondary motor-decrement">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="number" class="form-control text-center motor-quantity"
                                                    value="1" min="1">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary motor-increment">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control motor-price text-right"
                                                    placeholder="0" data-type="currency">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary select-motor" data-id="{{ $motor->id }}"
                                                data-name="{{ $motor->name }}" disabled>
                                                <i class="fas fa-check"></i> Pilih
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
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 25%">Name</th>
                                    <th class="text-center" style="width: 20%">Jumlah</th>
                                    <th class="text-center" style="width: 25%">Harga</th>
                                    <th class="text-center" style="width: 25%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spareParts as $index => $sparePart)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $sparePart->name }}</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-secondary spare-decrement">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="number" class="form-control text-center spare-quantity"
                                                    value="1" min="1">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary spare-increment">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control spare-price text-right"
                                                    placeholder="0" data-type="currency">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary select-spare-part"
                                                data-id="{{ $sparePart->id }}" data-name="{{ $sparePart->name }}"
                                                disabled>
                                                <i class="fas fa-check"></i> Pilih
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

    <!-- Add this to your scripts section -->
    <script>
        // Format currency input
        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function formatCurrency(input, blur) {
            // Get input value
            var input_val = input.val();

            // Don't validate empty input
            if (input_val === "") {
                return;
            }

            // Original length
            var original_len = input_val.length;

            // Initial caret position 
            var caret_pos = input.prop("selectionStart");

            // Check for decimal
            if (input_val.indexOf(".") >= 0) {
                // Get position of first decimal
                var decimal_pos = input_val.indexOf(".");

                // Split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // Add commas to left side of number
                left_side = formatNumber(left_side);

                // Validate right side
                right_side = formatNumber(right_side);

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // Join number by .
                input_val = left_side + "." + right_side;

            } else {
                // No decimal entered
                // Add commas to number
                input_val = formatNumber(input_val);
            }

            // Send updated string to input
            input.val(input_val);

            // Put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

        // Update price calculation functions
        function getNumericPrice(input) {
            return parseFloat(input.val().replace(/,/g, '')) || 0;
        }

        function updateMotorTotal(row) {
            const price = getNumericPrice($(row).find('.motor-price'));
            const quantity = parseInt($(row).find('.motor-quantity').val()) || 1;

            // Enable/disable select button based on price
            const selectButton = $(row).find('.select-motor');
            selectButton.prop('disabled', price <= 0);
        }

        function updateSpareTotal(row) {
            const price = getNumericPrice($(row).find('.spare-price'));
            const quantity = parseInt($(row).find('.spare-quantity').val()) || 1;

            // Enable/disable select button based on price
            const selectButton = $(row).find('.select-spare-part');
            selectButton.prop('disabled', price <= 0);
        }

        // Motor event handlers
        $('.motor-increment').click(function() {
            const input = $(this).closest('.input-group').find('.motor-quantity');
            input.val(parseInt(input.val()) + 1);
            updateMotorTotal($(this).closest('tr'));
        });

        $('.motor-decrement').click(function() {
            const input = $(this).closest('.input-group').find('.motor-quantity');
            if (parseInt(input.val()) > 1) {
                input.val(parseInt(input.val()) - 1);
                updateMotorTotal($(this).closest('tr'));
            }
        });

        $('.motor-price').on('input blur', function() {
            updateMotorTotal($(this).closest('tr'));
        });

        // Spare Part event handlers
        $('.spare-increment').click(function() {
            const input = $(this).closest('.input-group').find('.spare-quantity');
            input.val(parseInt(input.val()) + 1);
            updateSpareTotal($(this).closest('tr'));
        });

        $('.spare-decrement').click(function() {
            const input = $(this).closest('.input-group').find('.spare-quantity');
            if (parseInt(input.val()) > 1) {
                input.val(parseInt(input.val()) - 1);
                updateSpareTotal($(this).closest('tr'));
            }
        });

        $('.spare-price').on('input blur', function() {
            updateSpareTotal($(this).closest('tr'));
        });

        // Selection handlers
        $('.select-motor').click(function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = getNumericPrice(row.find('.motor-price'));
            const quantity = parseInt(row.find('.motor-quantity').val());
            const total = price * quantity;

            $('#motor_input').val(name);
            $('#motor_id').val(id);
            $('#harga').val(price);
            $('#jumlah').val(quantity);
            $('#total_harga').val(total);

            $('#motorModal').modal('hide');
        });

        $('.select-spare-part').click(function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = getNumericPrice(row.find('.spare-price'));
            const quantity = parseInt(row.find('.spare-quantity').val());
            const total = price * quantity;

            $('#spare_part_input').val(name);
            $('#spare_part_id').val(id);
            $('#harga').val(price);
            $('#jumlah').val(quantity);
            $('#total_harga').val(total);

            $('#sparePartModal').modal('hide');
        });
    </script>
@endsection
