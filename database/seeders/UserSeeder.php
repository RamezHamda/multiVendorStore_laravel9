<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ramez',
            'email' => 'ramez@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0599123456789'
        ]);

        DB::table('users')->insert([
            'name' => 'Ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0599123456'
        ]);

    }
}
