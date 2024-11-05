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
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('invoice')->unique();
            $table->foreignId('motor_id')->constrained('master_motors')->onDelete('cascade');
            $table->foreignId('spare_part_id')->constrained('master_spare_parts')->onDelete('cascade');
            $table->string('jumlah');
            $table->string('harga');
            $table->integer('order')->nullable(false);
            $table->timestamps();
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
