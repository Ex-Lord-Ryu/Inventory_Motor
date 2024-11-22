<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldSparePartsTable extends Migration
{
    public function up()
    {
        Schema::create('sold_spare_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);
            $table->timestamp('tanggal_terjual');
            $table->timestamps();

            $table->foreign('spare_part_id')->references('id')->on('master_spare_parts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sold_spare_parts');
    }
}