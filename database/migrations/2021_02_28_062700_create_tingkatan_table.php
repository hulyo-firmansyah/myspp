<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTingkatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tingkatan', function (Blueprint $table) {
            $table->increments('id_tingkatan');
            $table->enum('tingkatan', [10,11,12,13]);
            $table->timestamps();
        });
        
        DB::table('tingkatan')->insert([
            [
                'id_tingkatan' => 1,
                'tingkatan' => "10",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_tingkatan' => 2,
                'tingkatan' => "11",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_tingkatan' => 3,
                'tingkatan' => "12",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_tingkatan' => 4,
                'tingkatan' => "13",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tingkatan');
    }
}
