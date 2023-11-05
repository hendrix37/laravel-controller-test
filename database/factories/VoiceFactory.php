<?php

namespace Database\Factories;

use App\Models\Voice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voice>
 */
class VoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->create()->id,
            'question_id' => \App\Models\Question::factory()->create()->id,
            'value' => fake()->boolean(),
        ];
    }
}
