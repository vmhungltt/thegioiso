<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
    /*    DB::table('users')->insert([
            'name' => 'Vũ Minh Hùng',
            'email' => 'vuminhhungltt904@gmail.com',
            'password' => bcrypt('123456789a'),
        ]);*/
       /* DB::table('users')->insert([
            'name' => 'Trần Văn Hoàng',
            'email' => 'hoangtran123@gmail.com',
            'password' => bcrypt('123456789'),
        ]);*/
        DB::table('users')->insert([
            'name' => 'Trịnh Văn Khánh',
            'email' => 'khanhtrinh@gmail.com',
            'password' => bcrypt('123456789'),
            'login_type' => 1,
        ]);
    }
}
