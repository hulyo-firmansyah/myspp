<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeranjangTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keranjang_transaksi', function (Blueprint $table) {
            $table->increments('id_keranjang', 11);
            $table->integer('id_spp')->length(11)->unsigned();
            $table->foreign('id_spp')
                ->cascadeOnDelete()
                ->references('id_spp')
                ->on('spp');
            $table->integer('id_siswa')->length(11)->unsigned();
            $table->foreign('id_siswa')
                ->cascadeOnDelete()
                ->references('id_siswa')
                ->on('siswa');
            $table->integer('jumlah_bayar');
            $table->string('bulan_dibayar', 8);
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
        Schema::dropIfExists('keranjang_transaksi');
    }
}
