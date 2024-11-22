<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_detail_id')->nullable();
            $table->unsignedBigInteger('motor_id');
            $table->string('warna_id')->nullable();
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('nomor_rangka')->nullable();
            $table->string('nomor_mesin')->nullable();
            $table->string('type');
            $table->timestamps();

            $table->foreign('purchase_order_detail_id')->references('id')->on('purchase_orders_details');
            $table->foreign('motor_id')->references('id')->on('master_motors')->onDelete('cascade');
            $table->foreign('warna_id')->references('id')->on('master_warnas')->onDelete('set null');
        });

        Schema::create('stock_spare_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_detail_id')->nullable();
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('type');
            $table->timestamps();

            $table->foreign('purchase_order_detail_id')->references('id')->on('purchase_orders_details');
            $table->foreign('spare_part_id')->references('id')->on('master_spare_parts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_motors');
        Schema::dropIfExists('stock_spare_parts');
    }
};