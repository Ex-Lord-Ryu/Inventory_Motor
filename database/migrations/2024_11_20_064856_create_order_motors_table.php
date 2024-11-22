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
        Schema::create('order_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('motor_id');
            $table->string('warna_id');
            $table->string('nomor_rangka');
            $table->string('nomor_mesin');
            $table->decimal('harga_jual', 15, 2);
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('motor_id')->references('id')->on('master_motors');
            $table->foreign('warna_id')->references('id')->on('master_warnas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_motors');
    }
};
