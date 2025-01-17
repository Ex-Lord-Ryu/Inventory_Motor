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
        Schema::create('order_spare_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);
            $table->timestamp('tanggal_terjual');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('spare_part_id')->references('id')->on('master_spare_parts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_spare_parts');
    }
};
