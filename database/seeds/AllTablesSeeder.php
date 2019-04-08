<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class AllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '$2y$10$S8ZOu44JQg6oROx3fCw/cuYt2UDjNgvyS52gRRtKMytaePl.2lery',
        ]);

        DB::table('contacts')->insert([
            'user_id' => 1,
            'name' => Str::random(10),
            'surname' => Str::random(10),
            'image' => '5caa47dc670f8.jpg',
            'email' => Str::random(10).'@gmail.com',
            'favourite' => 0,
        ]);

        DB::table('contacts')->insert([
            'user_id' => 1,
            'name' => Str::random(10),
            'surname' => Str::random(10),
            'image' => '5caa47dc670f8.jpg',
            'email' => Str::random(10).'@gmail.com',
            'favourite' => 1,
        ]);

        DB::table('phones')->insert([
            'contact_id' => 1,
            'number' => '123123',
            'label' => 'work'
        ]);

        DB::table('phones')->insert([
            'contact_id' => 1,
            'number' => '5234',
            'label' => 'home'
        ]);
    }
}
