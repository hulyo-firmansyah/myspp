<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreatePetugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->increments('id_petugas', 11);
            $table->string('nama_petugas', 35);
            $table->integer('data_of')->unsigned();
            $table->foreign('data_of')
                ->cascadeOnDelete()
                ->references('id_user')
                ->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('petugas')->insert(
            [
                'nama_petugas' => 'Hulyo Firmansyah',
                'data_of' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petugas');
    }
}
