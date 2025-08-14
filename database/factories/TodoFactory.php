<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            "title" => $this->faker->sentence,
            "description" => $this->faker->paragraph,
            "user_id" => User::inRandomOrder()->first()->id,
            "status" => $this->faker->randomElement(["pending", "in_progress", "completed", "cancelled"]),
            "priority" => $this->faker->randomElement(["low", "medium", "high"]),
            "due_date" => $this->faker->date("Y-m-d"),

            'categories_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
