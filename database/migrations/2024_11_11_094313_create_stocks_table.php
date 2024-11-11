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
            $table->unsignedBigInteger('motor_id')->nullable();
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->string('warna_id')->nullable();
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->decimal('harga_jual_diskon', 15, 2)->nullable();
            $table->integer('nomor_rangka');
            $table->integer('nomor_mesin');
            $table->string('type');
            $table->integer('order')->default(0);
            $table->timestamps();

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
