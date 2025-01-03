<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/get-data', [HomeController::class, 'getData'])->name('get.data');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');

    Route::get('/user_management', [App\Http\Controllers\user_managementController::class, 'index'])->name('user_management.index')->middleware('superadmin');
    Route::get('/user_management/create', [App\Http\Controllers\user_managementController::class, 'create'])->name('user_management.create')->middleware('superadmin');
    Route::post('/user_management/store', [App\Http\Controllers\user_managementController::class, 'store'])->name('user_management.store')->middleware('superadmin');
    Route::get('/user_management', [App\Http\Controllers\user_managementController::class, 'index'])->name('user_management.index')->middleware('superadmin');
    Route::get('/user_management/edit/{id}', [App\Http\Controllers\user_managementController::class, 'edit'])->name('user_management.edit')->middleware('superadmin');
    Route::put('/user_management/update/{id}', [App\Http\Controllers\user_managementController::class, 'update'])->name('user_management.update')->middleware('superadmin');
    Route::delete('/user_management/delete/{id}', [App\Http\Controllers\user_managementController::class, 'destroy'])->name('user_management.delete')->middleware('superadmin');

    Route::get('/distributor', [App\Http\Controllers\DistributorController::class, 'index'])->name('distributor.index')->middleware('finance');
    Route::get('/distributor/create', [App\Http\Controllers\DistributorController::class, 'create'])->name('distributor.create')->middleware('finance');
    Route::post('/distributor/store', [App\Http\Controllers\DistributorController::class, 'store'])->name('distributor.store')->middleware('finance');
    Route::get('/distributor/edit/{id}', [App\Http\Controllers\DistributorController::class, 'edit'])->name('distributor.edit')->middleware('finance');
    Route::put('/distributor/update/{id}', [App\Http\Controllers\DistributorController::class, 'update'])->name('distributor.update')->middleware('finance');
    Route::delete('/distributor/delete/{id}', [App\Http\Controllers\DistributorController::class, 'destroy'])->name('distributor.delete')->middleware('finance');

    Route::get('/purchase_orders', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase_orders.index')->middleware('finance');
    Route::get('/purchase_orders/create', [App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('purchase_orders.create')->middleware('finance');
    Route::post('/purchase_orders/store', [App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('purchase_orders.store')->middleware('finance');
    Route::get('/purchase_orders/edit/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'edit'])->name('purchase_orders.edit')->middleware('finance');
    Route::put('/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\PurchaseOrderController::class, 'update'])->name('purchase_orders.update')->middleware('finance');
    Route::get('/purchase-orders/{id}/details', [\App\Http\Controllers\PurchaseOrderController::class, 'getDetails'])->name('purchase-orders.details')->middleware('finance');
    Route::delete('/purchase_orders/delete/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'destroy'])->name('purchase_orders.delete')->middleware('finance');
    Route::patch('/purchase_orders/{id}/cancel', [App\Http\Controllers\PurchaseOrderController::class, 'cancel'])->name('purchase_orders.cancel')->middleware('finance');
    Route::patch('/purchase_orders/{id}/complete', [App\Http\Controllers\PurchaseOrderController::class, 'complete'])->name('purchase_orders.complete')->middleware('finance');

    Route::get('/master_warna', [App\Http\Controllers\MasterWarnaController::class, 'index'])->name('master_warna.index')->middleware('admin',);
    Route::get('/master_warna/create', [App\Http\Controllers\MasterWarnaController::class, 'create'])->name('master_warna.create')->middleware('admin',);
    Route::post('/master_warna/store', [App\Http\Controllers\MasterWarnaController::class, 'store'])->name('master_warna.store')->middleware('admin',);
    Route::get('/master_warna/edit/{id}', [App\Http\Controllers\MasterWarnaController::class, 'edit'])->name('master_warna.edit')->middleware('admin',);
    Route::put('/master_warna/update/{id}', [App\Http\Controllers\MasterWarnaController::class, 'update'])->name('master_warna.update')->middleware('admin',);
    Route::delete('/master_warna/delete/{id}', [App\Http\Controllers\MasterWarnaController::class, 'destroy'])->name('master_warna.delete')->middleware('admin',);

    Route::get('/master_motor', [App\Http\Controllers\MasterMotorController::class, 'index'])->name('master_motor.index')->middleware('admin',);
    Route::get('/master_motor/create', [App\Http\Controllers\MasterMotorController::class, 'create'])->name('master_motor.create')->middleware('admin',);
    Route::post('/master_motor/store', [App\Http\Controllers\MasterMotorController::class, 'store'])->name('master_motor.store')->middleware('admin',);
    Route::get('/master_motor/edit/{id}', [App\Http\Controllers\MasterMotorController::class, 'edit'])->name('master_motor.edit')->middleware('admin',);
    Route::put('/master_motor/update/{id}', [App\Http\Controllers\MasterMotorController::class, 'update'])->name('master_motor.update')->middleware('admin',);
    Route::delete('/master_motor/delete/{id}', [App\Http\Controllers\MasterMotorController::class, 'destroy'])->name('master_motor.delete')->middleware('admin',);

    Route::get('/master_spare_parts', [App\Http\Controllers\MasterSparePartController::class, 'index'])->name('master_spare_parts.index')->middleware('admin',);
    Route::get('/master_spare_parts/create', [App\Http\Controllers\MasterSparePartController::class, 'create'])->name('master_spare_parts.create')->middleware('admin',);
    Route::post('/master_spare_parts/store', [App\Http\Controllers\MasterSparePartController::class, 'store'])->name('master_spare_parts.store')->middleware('admin',);
    Route::get('/master_spare_parts/edit/{id}', [App\Http\Controllers\MasterSparePartController::class, 'edit'])->name('master_spare_parts.edit')->middleware('admin',);
    Route::put('/master_spare_parts/update/{id}', [App\Http\Controllers\MasterSparePartController::class, 'update'])->name('master_spare_parts.update')->middleware('admin',);
    Route::delete('/master_spare_parts/delete/{id}', [App\Http\Controllers\MasterSparePartController::class, 'destroy'])->name('master_spare_parts.delete')->middleware('admin',);

    Route::get('/purchase_orders_details', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'index'])->name('purchase_orders_details.index')->middleware('admin',);
    Route::get('/purchase_orders_details/{id}/show', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'show'])->name('purchase_orders_details.show')->middleware('admin',);
    Route::get('/purchase_orders_details/create', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'create'])->name('purchase_orders_details.create')->middleware('admin',);
    Route::post('/purchase_orders_details/store', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'store'])->name('purchase_orders_details.store')->middleware('admin',);
    Route::delete('/purchase_orders_details/delete/{id}', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'destroy'])->name('purchase_orders_details.delete')->middleware('admin',);
    Route::patch('/purchase_orders_details/{id}/cancel', [App\Http\Controllers\PurchaseOrdersDetailsController::class, 'cancel'])->name('purchase_orders_details.cancel')->middleware('admin',);

    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index')->middleware('admin',);
    Route::get('/stock/{id}/show', [App\Http\Controllers\StockController::class, 'show'])->name('stock.show')->middleware('admin',);
    Route::patch('/stock/update-type', [\App\Http\Controllers\StockController::class, 'updateType'])->name('stock.updateType')->middleware('admin',);
    Route::post('/add-to-stock/{invoice}', [\App\Http\Controllers\StockController::class, 'addToStock'])->name('add.to.stock')->middleware('admin',);
    Route::put('/stock/{id}/update-details', [\App\Http\Controllers\StockController::class, 'updateDetails'])->name('stock.update-details')->middleware('admin',);
    Route::get('/stock/{id}/{type}/edit-pricing', [\App\Http\Controllers\StockController::class, 'editPricing'])->name('stock.edit-pricing')->middleware('admin',);
    Route::put('/stock/{id}/{type}/update-details', [\App\Http\Controllers\StockController::class, 'updateDetails'])->name('stock.update-details')->middleware('admin',);
    Route::get('/stock/input-motor', [\App\Http\Controllers\StockController::class, 'inputMotorData'])->name('stock.input-motor')->middleware('admin',);
    Route::post('/stock/store-motor', [\App\Http\Controllers\StockController::class, 'storeMotorData'])->name('stock.store-motor-data')->middleware('admin',);
    Route::post('/stock/sell-motor/{id}', [\App\Http\Controllers\StockController::class, 'sellMotor'])->name('stock.sell-motor')->middleware('admin',);
    Route::post('/stock/sell-spare-part/{id}', [\App\Http\Controllers\StockController::class, 'sellSparePart'])->name('stock.sell-spare-part')->middleware('admin',);
    Route::get('/stock/sold-items', [\App\Http\Controllers\StockController::class, 'soldItems'])->name('stock.sold-items')->middleware('admin',);
    Route::get('/stock/all', [App\Http\Controllers\StockController::class, 'allStock'])->name('stock.all')->middleware('admin');

    Route::get('/order_motor', [\App\Http\Controllers\OrderMotorController::class, 'index'])->name('order_motor.index')->middleware( 'sales');
    Route::post('/order_motor', [\App\Http\Controllers\OrderMotorController::class, 'store'])->name('order_motor.store')->middleware( 'sales');

    Route::get('/order_spare_parts', [App\Http\Controllers\OrderSparePartController::class, 'index'])->name('order_spare_parts.index')->middleware( 'sales');
    Route::post('/order_spare_parts', [App\Http\Controllers\OrderSparePartController::class, 'store'])->name('order_spare_parts.store')->middleware( 'sales');

    Route::get('/sales_report', [\App\Http\Controllers\SalesReportController::class, 'index'])->name('sales_report.index');
    Route::get('/sales_report/export-excel', [\App\Http\Controllers\SalesReportController::class, 'exportExcel'])->name('sales_report.export_excel');
    Route::get('/sales_report/export-pdf', [\App\Http\Controllers\SalesReportController::class, 'exportPdf'])->name('sales_report.export_pdf');
});
