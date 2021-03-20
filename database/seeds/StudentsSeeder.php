<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i=1; $i<20; $i++){
            $id = DB::table('users')->insertGetId([
                'username' => $faker->userName,
                'password' => bcrypt('admin'),
                'email' => $faker->email,
                'role' => 'student',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'remember_token' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ], 'id_user');

            // $id_spp = DB::table('spp')->insertGetId([
            //     'tahun' => rand(2015, 2022),
            //     'nominal' => 2000000,
            //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            //     'deleted_at' => null,
            // ]);
            
            DB::table('siswa')->insert([
                'nisn' => $faker->randomNumber(8),
                'nis' => $faker->randomNumber(8),
                'nama' => $faker->name,
                'id_kelas' => 1,
                'alamat' => $faker->address,
                'no_telp' => $faker->randomNumber(6),
                // 'id_spp' => $id_spp,
                'data_of' => $id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }
    }
}
