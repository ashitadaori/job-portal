<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle(),
            'employer_id' => User::where('role', 'employer')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'employer'])->id,
            'job_type_id' => rand(1, 5),
            'category_id' => rand(1, 5),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'benefits' => $this->faker->paragraphs(2, true),
            'salary_range' => $this->faker->randomElement(['$30k-50k', '$50k-80k', '$80k-120k', '$120k+']),
            'location' => $this->faker->city(),
            'status' => true,
            'featured' => $this->faker->boolean(20)
        ];
    }
}
