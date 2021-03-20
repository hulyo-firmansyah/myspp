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
            $table->string('kompetensi_keahlian', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('kelas')->insert([
            'nama_kelas' => 'Default',
            'tingkatan' => 10,
            'kompetensi_keahlian' => 'RPL 1',
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
