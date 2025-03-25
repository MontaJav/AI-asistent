<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition()
    {
        $courseIds = Course::pluck('id')->toArray();
        $start = $this->faker->dateTimeBetween('+1 day', '+1 year');

        return [
            'course_id' => $this->faker->randomElement($courseIds),
            'start' => $start->format('Y-m-d H:00:00'),
            'end' => $start->modify('+1 hour')->format('Y-m-d H:00:00'),
            'description' => $this->faker->paragraph,
            'mandatory' => $this->faker->boolean,
            'cancelled_at' => null,
        ];
    }
}
