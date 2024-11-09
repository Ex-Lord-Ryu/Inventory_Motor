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
            width: 80px !important;
            text-align: center;
        }

        .price-input {
            text-align: right !important;
        }

        .total-column {
            font-weight: bold;
            text-align: right;
        }

        .form-control {
            height: calc(1.5em + .75rem + 2px);
        }

        .modal-lg {
            max-width: 1200px;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                min-height: calc(100% - 3.5rem);
            }
        }

        .modal-content {
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal {
            overflow-y: auto;
        }

        @media (min-width: 992px) {
            .modal-lg {
                max-width: 80%;
            }
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        .selected-items {
            margin-bottom: 20px;
        }

        .selected-item .btn {
            margin-right: 5px;
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

    <!-- Motor Modal -->
    <div class="modal fade" id="motorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Motor</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height: 60vh;">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%"><input type="checkbox" id="selectAllMotors">
                                    </th>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 20%">Name</th>
                                    <th class="text-center" style="width: 15%">Color</th>
                                    <th class="text-center" style="width: 15%">Quantity</th>
                                    <th class="text-center" style="width: 20%">Price</th>
                                    <th class="text-center" style="width: 15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($motors as $index => $motor)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="motor-checkbox"
                                                data-id="{{ $motor->id }}"></td>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $motor->nama_motor }}</td>
                                        <td>
                                            <select class="form-control motor-color">
                                                @foreach ($masterWarna as $warna)
                                                    <option value="{{ $warna->id }}">{{ $warna->nama_warna }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="number"
                                                    class="form-control motor-quantity quantity-input text-center"
                                                    value="1" min="1" style="width: 80px;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text"
                                                    class="form-control motor-price price-input text-right"
                                                    placeholder="0" data-type="currency">
                                            </div>
                                        </td>
                                        <td class="total-column motor-total text-right">Rp 0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addSelectedMotors">Add Selected Motors</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Spare Part Modal -->
    <div class="modal fade" id="sparePartModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Spare Part</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height: 60vh;">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%"><input type="checkbox"
                                            id="selectAllSpareParts"></th>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 25%">Name</th>
                                    <th class="text-center" style="width: 15%">Quantity</th>
                                    <th class="text-center" style="width: 25%">Price</th>
                                    <th class="text-center" style="width: 20%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spareParts as $index => $sparePart)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" class="spare-part-checkbox"
                                                data-id="{{ $sparePart->id }}"></td>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $sparePart->nama_spare_part }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <input type="number"
                                                    class="form-control spare-quantity quantity-input text-center"
                                                    value="1" min="1" style="width: 80px;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text"
                                                    class="form-control spare-price price-input text-right"
                                                    placeholder="0" data-type="currency">
                                            </div>
                                        </td>
                                        <td class="total-column spare-total text-right">Rp 0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addSelectedSpareParts">Add Selected Spare
                        Parts</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedMotors = new Map();
            let selectedSpareParts = new Map();
            let isSubmitting = false;

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

            $('#selectAllMotors').on('change', function() {
                $('.motor-checkbox').prop('checked', $(this).is(':checked'));
            });

            $('#selectAllSpareParts').on('change', function() {
                $('.spare-part-checkbox').prop('checked', $(this).is(':checked'));
            });

            $('#addSelectedMotors').on('click', function() {
                $('.motor-checkbox:checked').each(function() {
                    const row = $(this).closest('tr');
                    const id = $(this).data('id');
                    const name = row.find('td:eq(2)').text();
                    const quantity = parseInt(row.find('.motor-quantity').val()) || 1;
                    const price = parseNumber(row.find('.motor-price').val());
                    const colorId = row.find('.motor-color').val();
                    const colorName = row.find('.motor-color option:selected').text();

                    if (price <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Please enter a valid price for the motor: ${name}`
                        });
                        return;
                    }

                    const uniqueKey = `${id}-${colorId}`;

                    if (!selectedMotors.has(uniqueKey)) {
                        selectedMotors.set(uniqueKey, {
                            id: id,
                            name: name,
                            quantity: quantity,
                            price: price,
                            colorId: colorId,
                            colorName: colorName
                        });
                    } else {
                        const existingItem = selectedMotors.get(uniqueKey);
                        existingItem.quantity += quantity;
                        selectedMotors.set(uniqueKey, existingItem);
                    }
                });

                updateMotorSelection();
                $('#motorModal').modal('hide');
            });

            $('#addSelectedSpareParts').on('click', function() {
                $('.spare-part-checkbox:checked').each(function() {
                    const row = $(this).closest('tr');
                    const id = $(this).data('id');
                    const name = row.find('td:eq(2)').text();
                    const quantity = parseInt(row.find('.spare-quantity').val()) || 1;
                    const price = parseNumber(row.find('.spare-price').val());

                    if (price <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Please enter a valid price for the spare part: ${name}`
                        });
                        return;
                    }

                    selectedSpareParts.set(id, {
                        id: id,
                        name: name,
                        quantity: quantity,
                        price: price
                    });
                });

                updateSparePartSelection();
                $('#sparePartModal').modal('hide');
            });

            function updateMotorSelection() {
                const container = $('#motorSelection');
                container.empty();
                selectedMotors.forEach((item, key) => {
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
                if (type === 'motor') {
                    return `
            <div class="selected-item ${type}-item row mb-2">
                <input type="hidden" name="${type}_ids[]" value="${item.id}">
                <input type="hidden" name="${type}_quantities[]" value="${item.quantity}">
                <input type="hidden" name="${type}_prices[]" value="${item.price}">
                <input type="hidden" name="${type}_warna_ids[]" value="${item.colorId}">
                <div class="col-md-3">${item.name}</div>
                <div class="col-md-2">${item.colorName}</div>
                <div class="col-md-2">
                    <input type="number" class="form-control ${type}-quantity" value="${item.quantity}" min="1">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control ${type}-price" value="${formatNumber(item.price)}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm edit-${type}" data-id="${item.id}" data-color-id="${item.colorId}">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm remove-${type}" data-id="${item.id}" data-color-id="${item.colorId}">Remove</button>
                </div>
            </div>`;
                } else {
                    return `
            <div class="selected-item ${type}-item row mb-2">
                <input type="hidden" name="${type}_ids[]" value="${item.id}">
                <input type="hidden" name="${type}_quantities[]" value="${item.quantity}">
                <input type="hidden" name="${type}_prices[]" value="${item.price}">
                <div class="col-md-4">${item.name}</div>
                <div class="col-md-2">
                    <input type="number" class="form-control ${type}-quantity" value="${item.quantity}" min="1">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control ${type}-price" value="${formatNumber(item.price)}">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-sm edit-${type}" data-id="${item.id}">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm remove-${type}" data-id="${item.id}">Remove</button>
                </div>
            </div>`;
                }
            }

            $(document).on('click', '.remove-motor', function() {
                const id = $(this).data('id');
                const colorId = $(this).data('color-id');
                const uniqueKey = `${id}-${colorId}`;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to remove this motor`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        selectedMotors.delete(uniqueKey);
                        updateMotorSelection();
                        Swal.fire(
                            'Removed!',
                            `The motor has been removed.`,
                            'success'
                        )
                    }
                })
            });

            $(document).on('click', '.remove-spare-part', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to remove this spare part`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        selectedSpareParts.delete(id);
                        updateSparePartSelection();
                        Swal.fire(
                            'Removed!',
                            `The spare part has been removed.`,
                            'success'
                        )
                    }
                })
            });

            $(document).on('click', '.edit-motor', function() {
                const id = $(this).data('id');
                const colorId = $(this).data('color-id');
                const uniqueKey = `${id}-${colorId}`;
                const item = selectedMotors.get(uniqueKey);
                const itemContainer = $(this).closest('.selected-item');

                Swal.fire({
                    title: 'Edit Motor',
                    html: `<input id="edit-quantity" class="swal2-input" type="number" value="${item.quantity}" min="1">` +
                        `<input id="edit-price" class="swal2-input" type="text" value="${formatNumber(item.price)}">`,
                    focusConfirm: false,
                    preConfirm: () => {
                        const newQuantity = parseInt(document.getElementById('edit-quantity')
                            .value);
                        const newPrice = parseNumber(document.getElementById('edit-price')
                            .value);
                        return {
                            quantity: newQuantity,
                            price: newPrice
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const {
                            quantity,
                            price
                        } = result.value;
                        if (quantity > 0 && price > 0) {
                            item.quantity = quantity;
                            item.price = price;
                            selectedMotors.set(uniqueKey, item);
                            itemContainer.find(`.motor-quantity`).val(quantity);
                            itemContainer.find(`.motor-price`).val(formatNumber(price));
                            itemContainer.find(`input[name="motor_quantities[]"]`).val(quantity);
                            itemContainer.find(`input[name="motor_prices[]"]`).val(price);
                            updateTotalPrice();
                        } else {
                            Swal.fire('Error', 'Invalid quantity or price', 'error');
                        }
                    }
                });
            });

            $(document).on('click', '.edit-spare-part', function() {
                const id = $(this).data('id');
                const item = selectedSpareParts.get(id);
                const itemContainer = $(this).closest('.selected-item');

                Swal.fire({
                    title: 'Edit Spare Part',
                    html: `<input id="edit-quantity" class="swal2-input" type="number" value="${item.quantity}" min="1">` +
                        `<input id="edit-price" class="swal2-input" type="text" value="${formatNumber(item.price)}">`,
                    focusConfirm: false,
                    preConfirm: () => {
                        const newQuantity = parseInt(document.getElementById('edit-quantity')
                            .value);
                        const newPrice = parseNumber(document.getElementById('edit-price')
                            .value);
                        return {
                            quantity: newQuantity,
                            price: newPrice
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const {
                            quantity,
                            price
                        } = result.value;
                        if (quantity > 0 && price > 0) {
                            item.quantity = quantity;
                            item.price = price;
                            selectedSpareParts.set(id, item);
                            itemContainer.find(`.spare-quantity`).val(quantity);
                            itemContainer.find(`.spare-price`).val(formatNumber(price));
                            itemContainer.find(`input[name="spare_part_quantities[]"]`).val(
                                quantity);
                            itemContainer.find(`input[name="spare_part_prices[]"]`).val(price);
                            updateTotalPrice();
                        } else {
                            Swal.fire('Error', 'Invalid quantity or price', 'error');
                        }
                    }
                });
            });

            $('form').off('submit').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) return;

                const poId = $('#purchase_order_id').val();
                if (!poId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan pilih Purchase Order'
                    });
                    return;
                }

                if (selectedMotors.size === 0 && selectedSpareParts.size === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan pilih setidaknya satu motor atau spare part'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menyimpan data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const submitButton = $(this).find('button[type="submit"]');
                        submitButton.prop('disabled', true);
                        isSubmitting = true;

                        const formData = new FormData();
                        formData.append('purchase_order_id', poId);

                        selectedMotors.forEach((item, key) => {
                            formData.append('motor_ids[]', item.id);
                            formData.append('motor_quantities[]', item.quantity);
                            formData.append('motor_prices[]', item.price);
                            formData.append('motor_warna_ids[]', item.colorId);
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data berhasil disimpan!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.href = response
                                        .redirect;
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menyimpan data'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menyimpan data'
                                });
                            },
                            complete: function() {
                                submitButton.prop('disabled', false);
                                isSubmitting = false;
                            }
                        });
                    }
                });
            });

            $(document).on('input', '.price-input', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(formatNumber(value));
            });

            $(document).on('input', '.motor-quantity, .motor-price, .spare-quantity, .spare-price', function() {
                const row = $(this).closest('tr');
                const quantity = parseInt(row.find('.motor-quantity, .spare-quantity').val()) || 0;
                const price = parseNumber(row.find('.motor-price, .spare-price').val());
                const total = quantity * price;
                row.find('.motor-total, .spare-total').text('Rp ' + formatNumber(total));
            });

            $(document).on('input',
                '.selected-item .motor-quantity, .selected-item .motor-price, .selected-item .spare-quantity, .selected-item .spare-price',
                function() {
                    const item = $(this).closest('.selected-item');
                    const id = item.find('button').data('id');
                    const colorId = item.find('button').data('color-id');
                    const type = item.hasClass('motor-item') ? 'motor' : 'spare-part';
                    const quantity = parseInt(item.find(`.${type}-quantity`).val()) || 0;
                    const price = parseNumber(item.find(`.${type}-price`).val());

                    if (type === 'motor') {
                        const uniqueKey = `${id}-${colorId}`;
                        const motorItem = selectedMotors.get(uniqueKey);
                        motorItem.quantity = quantity;
                        motorItem.price = price;
                        selectedMotors.set(uniqueKey, motorItem);
                    } else {
                        const sparePartItem = selectedSpareParts.get(id);
                        sparePartItem.quantity = quantity;
                        sparePartItem.price = price;
                        selectedSpareParts.set(id, sparePartItem);
                    }

                    item.find(`input[name="${type}_quantities[]"]`).val(quantity);
                    item.find(`input[name="${type}_prices[]"]`).val(price);

                    updateTotalPrice();
                });

            $('[data-toggle="tooltip"]').tooltip();

            $('.motor-color').on('change', function() {
                const row = $(this).closest('tr');
                const motorId = row.find('.motor-checkbox').data('id');
                const colorId = $(this).val();
                const colorName = $(this).find('option:selected').text();

                row.find('.motor-checkbox').data('color-id', colorId);
                row.find('.motor-checkbox').data('color-name', colorName);
            });

            $('#purchase_order_id').on('change', function() {
                const poId = $(this).val();
                if (poId) {
                    $.ajax({
                        url: `/purchase-orders/${poId}/details`,
                        method: 'GET',
                        success: function(response) {
                            $('#vendor_name').text(response.vendor_name);
                            $('#po_status').text(response.status);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat mengambil detail Purchase Order'
                            });
                        }
                    });
                } else {
                    $('#vendor_name').text('-');
                    $('#po_status').text('-');
                }
            });
        });
    </script>
@endpush
