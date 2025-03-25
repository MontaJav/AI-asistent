<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    public function definition()
    {
        $courseIds = Course::pluck('id')->toArray();

        return [
            'course_id' => $this->faker->randomElement($courseIds),
            'description' => $this->faker->paragraph,
            'due_at' => $this->faker->dateTimeBetween('+1 week', '+8 months')
                ->format('Y-m-d 00:00:00'),
        ];
    }
}
