<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $creditpoints = (int)$this->faker->randomFloat(0, 1, 4);

        return [
            'name' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'teacher' => $this->faker->name,
            'duration' => $creditpoints * 10,
            'creditpoints' => $creditpoints,
            'mandatory' => $this->faker->boolean,
        ];
    }
}
