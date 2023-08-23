<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'      => 'Programador',
                'email'     => env('PROGRAMMER_EMAIL'),
                'password'  => bcrypt(env('PROGRAMMER_PASSWD')),
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Administrator',
                'email'     => env('ADMIN_EMAIL'),
                'password'  => bcrypt(env('ADMIN_PASSWD')),
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Coordenador',
                'email'     => env('COORD_EMAIL'),
                'password'  => bcrypt(env('COORD_PASSWD')),
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Guerreiro',
                'email'     => env('CYBER_EMAIL'),
                'password'  => bcrypt(env('CYBER_PASSWD')),
                'created_at' => new DateTime('now')
            ],
        ]);
    }
}
