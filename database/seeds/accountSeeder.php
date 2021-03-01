<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;  

class accountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $role = ['admin', 'worker'];
        
        for($i=0; $i<5; $i++){
            if($i == 0){
                $id = DB::table('users')->insertGetId([
                    'username' => $faker->userName,
                    'password' => bcrypt('admin'),
                    'email' => $faker->email,
                    'role' => $role[0],
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'remember_token' => null,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ], 'id_user');
                
                DB::table('petugas')->insert([
                    'nama_petugas' => $faker->name,
                    'data_of' => $id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ]);
            }else{
                $id = DB::table('users')->insertGetId([
                    'username' => $faker->userName,
                    'password' => bcrypt('admin'),
                    'email' => $faker->email,
                    'role' => $role[1],
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'remember_token' => null,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ], 'id_user');
                
                DB::table('petugas')->insert([
                    'nama_petugas' => $faker->name,
                    'data_of' => $id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ]);
            }
        }
    }
}
