<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JobTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jobTypes = [
            'Full Time',
            'Part Time',
            'Contract',
            'Freelance',
            'Internship',
            'Remote',
            'Temporary'
        ];

        $name = $this->faker->unique()->randomElement($jobTypes);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => true
        ];
    }
}
