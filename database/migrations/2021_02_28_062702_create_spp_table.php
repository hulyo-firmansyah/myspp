<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spp', function (Blueprint $table) {
            $table->increments('id_spp', 11);
            $table->string('tahun', 9);
            $table->integer('nominal')->length(11);
            // $table->integer('periode')->length(11); hapus dulu sementara keknya ga bakal kepake
            
            $table->integer('id_tingkatan')->length(11)->unsigned();
            $table->foreign('id_tingkatan')
                ->onDelete('restrict')
                ->onUpdate('restrict')
                ->references('id_tingkatan')
                ->on('tingkatan');
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
        Schema::dropIfExists('spp');
    }
}
