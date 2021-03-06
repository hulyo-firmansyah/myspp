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
            $table->string('kode_pembayaran', 9)->unique();
            $table->integer('id_petugas')->length(11)->unsigned();
            $table->foreign('id_petugas')
                ->cascadeOnDelete()
                ->references('id_petugas')
                ->on('petugas');
            $table->integer('id_siswa')->length(11)->unsigned();
            $table->foreign('id_siswa')
                ->cascadeOnDelete()
                ->references('id_siswa')
                ->on('siswa');
            $table->timestamp('tgl_bayar');
            $table->string('bulan_dibayar', 8);
            $table->string('tahun_dibayar', 4);
            $table->integer('id_spp')->length(11)->unsigned();
            $table->foreign('id_spp')
                ->cascadeOnDelete()
                ->references('id_spp')
                ->on('spp');
            $table->integer('jumlah_bayar')->length(11);
            $table->enum('jenis_pembayaran', ['cash', 'transfer', 'gopay', 'ovo', 'indomaret', 'alfamart']);
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
