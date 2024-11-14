<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_detail_id')->nullable();
            $table->unsignedBigInteger('motor_id')->nullable();
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->string('warna_id')->nullable();
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->decimal('harga_jual_diskon', 15, 2)->nullable();
            $table->string('nomor_rangka')->nullable();
            $table->string('nomor_mesin')->nullable();
            $table->decimal('diskon_persen', 5, 2)->nullable();
            $table->decimal('diskon_nilai', 15, 2)->nullable();
            $table->string('type');
            $table->timestamps();

            $table->foreign('purchase_order_detail_id')->references('id')->on('purchase_orders_details');
            $table->foreign('motor_id')->references('id')->on('master_motors')->onDelete('set null');
            $table->foreign('spare_part_id')->references('id')->on('master_spare_parts')->onDelete('set null');
            $table->foreign('warna_id')->references('id')->on('master_warnas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
