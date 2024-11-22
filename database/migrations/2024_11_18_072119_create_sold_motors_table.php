<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldMotorsTable extends Migration
{
    public function up()
    {
        Schema::create('sold_motors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motor_id');
            $table->string('warna_id');
            $table->string('nomor_rangka');
            $table->string('nomor_mesin');
            $table->decimal('harga_jual', 15, 2);
            $table->timestamp('tanggal_terjual');
            $table->timestamps();

            $table->foreign('motor_id')->references('id')->on('master_motors');
            $table->foreign('warna_id')->references('id')->on('master_warnas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sold_motors');
    }
}