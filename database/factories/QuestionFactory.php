<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Question::class;

    public function definition()
    {
        return [
            "text"      => $this->faker->text(),
            "city_id"   => \App\Models\City::all()->random()->id,
            "user_id"   => \App\Models\User::all()->random()->id,

        ];
    }
}
