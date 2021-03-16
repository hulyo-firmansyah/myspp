<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id_pembayaran', 11);
            $table->integer('id_petugas')->length(11)->unsigned();
            $table->foreign('id_petugas')
                ->cascadeOnDelete()
                ->references('id_petugas')
                ->on('petugas');
            $table->string('nisn', 10);
            $table->foreign('nisn')
                ->cascadeOnDelete()
                ->references('nisn')
                ->on('siswa');
            $table->date('tgl_bayar');
            $table->string('bulan_dibayar', 8);
            $table->string('tahun_dibayar', 4);
            $table->integer('id_spp')->length(11)->unsigned();
            $table->foreign('id_spp')
                ->cascadeOnDelete()
                ->references('id_spp')
                ->on('spp');
            $table->integer('jumlah_bayar')->length(11);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
