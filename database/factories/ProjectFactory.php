<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->assignRole(fake()->randomElement(['manager', 'worker']))->id,
            'client_id' => Client::factory()->create()->id,
            'title' => fake()->domainName(),
            'description' => fake()->paragraph(),
            'deadline' => fake()->date(),
            'status' => fake()->randomElement(Project::STATUS),
        ];
    }
}
