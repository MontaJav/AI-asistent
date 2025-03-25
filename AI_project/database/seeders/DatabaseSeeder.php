<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => env('STUDENT_EMAIL')], [
            'name' => env('STUDENT_NAME'),
            'email' => env('STUDENT_EMAIL'),
            'password' => Hash::make(env('STUDENT_PASSWORD')),
        ]);

        Lesson::truncate();
        Assignment::truncate();
        Course::truncate();

        Course::factory()->count(10)->create();
        Assignment::factory()->count(100)->create();
        Lesson::factory()->count(100)->create();
    }
}
