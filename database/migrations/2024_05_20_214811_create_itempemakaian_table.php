<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itempemakaian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemakaian')->nullable();
            $table->string('barang_asal')->nullable();
            $table->decimal('qty_asal', 15,2)->nullable();
            $table->string('barang_tujuan')->nullable();
            $table->decimal('qty_tujuan', 15,2)->nullable();
            $table->decimal('harga', 15,2)->nullable();
            $table->decimal('biaya', 15,2)->nullable();
            $table->decimal('total', 15,2)->nullable();
            $table->string('supplier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itempemakaian');
    }
};
