<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkshopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workshops')->insert([
            'title' => "Wednesday 10:00 am",
            'assessment_id' => 1,
            'active_status' => True,
            ]); 
        DB::table('workshops')->insert([
            'title' => "Wednesday 10:00 am",
            'assessment_id' => 2,
            'active_status' => True,
            ]); 
        DB::table('workshops')->insert([
            'title' => "Wednesday 10:00 am",
            'assessment_id' => 3,
            'active_status' => True,
            ]); 
    }
}
