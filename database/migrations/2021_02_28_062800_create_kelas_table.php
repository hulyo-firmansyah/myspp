<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->increments('id_kelas', 11);
            $table->string('nama_kelas', 10);
            $table->integer('tingkatan');
            $table->integer('id_kompetensi_keahlian')->unsigned();
            $table->foreign('id_kompetensi_keahlian')
                ->cascadeOnDelete()
                ->references('id_kompetensi_keahlian')
                ->on('kompetensi_keahlian');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('kelas')->insert([
            'nama_kelas' => 'Default',
            'tingkatan' => 10,
            'id_kompetensi_keahlian' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelas');
    }
}
