<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->increments('id_siswa', 11);
            $table->char('nisn', 10)->unique();
            $table->char('nis', 8);
            $table->string('nama', 35);
            $table->integer('id_kelas')->length(11)->unsigned();
            $table->foreign('id_kelas')
                ->cascadeOnDelete()
                ->references('id_kelas')
                ->on('kelas');
            $table->text('alamat');
            $table->string('no_telp', 13);
            $table->integer('data_of')->unsigned();
            $table->foreign('data_of')
                ->cascadeOnDelete()
                ->references('id_user')
                ->on('users');
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
        Schema::dropIfExists('siswa');
    }
}
