<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('assessments')->insert([
            'title' => 'Week 1 Peer Review',
            'instruction' => 'Submit a peer review of your group members.',
            'review' => null, 
            'due_date' => '2024-10-02 23:59',
            'max_score' => 100,
            'type' => 'student-select',
            'required_reviews' => 3,
            'course_id' => 1, 
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        DB::table('assessments')->insert([
            'title' => 'Week 2 Peer Review',
            'instruction' => 'Submit a peer review of your group members.',
            'review' => null, 
            'due_date' => '2024-10-02 23:59',
            'max_score' => 100,
            'type' => 'student-select',
            'required_reviews' => 3,
            'course_id' => 1, 
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        DB::table('assessments')->insert([
            'title' => 'Week 1 Peer Review',
            'instruction' => 'Submit a peer review of your group members.',
            'review' => null, 
            'due_date' => '2024-10-02 23:59',
            'max_score' => 100,
            'type' => 'student-select',
            'required_reviews' => 3,
            'course_id' => 2, 
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        DB::table('assessments')->insert([
            'title' => 'Week 1 Peer Review',
            'instruction' => 'Submit a peer review of your group members.',
            'review' => null, 
            'due_date' =>'2024-10-02 23:59',
            'max_score' => 100,
            'type' => 'student-select',
            'required_reviews' => 3,
            'course_id' => 2, 
            'updated_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
    }
}
