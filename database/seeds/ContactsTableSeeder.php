<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->insert([
            'user_id' => 1,
            'name' => Str::random(10),
            'surname' => Str::random(10),
            'image' => 'image.jpg',
            'email' => Str::random(10).'@gmail.com',
            'favourite' => 0,
        ]);

        DB::table('contacts')->insert([
            'user_id' => 1,
            'name' => Str::random(10),
            'surname' => Str::random(10),
            'image' => 'image.jpg',
            'email' => Str::random(10).'@gmail.com',
            'favourite' => 1,
        ]);
    }
}
