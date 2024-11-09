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
        Schema::create('purchase_orders_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('motor_id')->nullable();
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->string('warna_id')->nullable();
            $table->integer('jumlah'); // Mengganti quantity menjadi jumlah
            $table->decimal('harga', 15, 2)->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->string('invoice')->nullable();
            $table->string('status')->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_orders_details');
    }
};
