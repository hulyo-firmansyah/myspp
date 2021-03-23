<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompetensi_keahlian', function (Blueprint $table) {
            $table->increments('id_kompetensi_keahlian');
            $table->string('kompetensi_keahlian', 100);
            $table->timestamps();
            $table->softDeletes();

            DB::table('kelas')->insert([
                'kompetensi_keahlian' => 'RPL 1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurusan');
    }
}
