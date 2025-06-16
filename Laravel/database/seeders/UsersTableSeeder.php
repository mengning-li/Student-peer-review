<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Bob",
            'email' => 'bob@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's111111',
            'role' => 'student',
            ]); 
        DB::table('users')->insert([
            'name' => "Fred",
            'email' => 'fred@gmail.com', 
            'password' => bcrypt('12341234'),
            'sNumber' => 's222222',
            'role' => 'student',
            ]);
        DB::table('users')->insert([
            'name' => "Tony",
            'email' => 'tony@gmail.com', 
            'password' => bcrypt('12341234'),
            'sNumber' => 's333333',
            'role' => 'student',
            ]);
        DB::table('users')->insert([
            'name' => "Jim",
            'email' => 'jim@gmail.com', 
            'password' => bcrypt('12341234'),
            'sNumber' => 's444444',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Alice",
            'email' => 'alice@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222223',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Charlie",
            'email' => 'charlie@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222225',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "David",
            'email' => 'david@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222226',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Eva",
            'email' => 'eva@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222227',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "George",
            'email' => 'george@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222228',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Helen",
            'email' => 'helen@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222229',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Ivy",
            'email' => 'ivy@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222230',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "Jack",
            'email' => 'jack@gmail.com',
            'password' => bcrypt('12341234'),
            'sNumber' => 's222231',
            'role' => 'student',
        ]);
        DB::table('users')->insert([
            'name' => "John",
            'email' => 'john@gmail.com', 
            'password' => bcrypt('12341234'),
            'sNumber' => 's555555',
            'role' => 'teacher',
        ]);
    }
}
