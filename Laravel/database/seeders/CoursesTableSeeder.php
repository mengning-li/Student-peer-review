<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            'code' => 'ICT101',
            'name' => "Introduction to Information Technology",
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        
        DB::table('courses')->insert([
            'code' => 'ICT102',
            'name' => "Programming Fundamentals",
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        
        DB::table('courses')->insert([
            'code' => 'ICT201',
            'name' => "Web Application Development",
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        
        DB::table('courses')->insert([
            'code' => 'ICT202',
            'name' => "Database Design",
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        
        DB::table('courses')->insert([
            'code' => 'ICT301',
            'name' => "Software Development",
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        
    }
}
