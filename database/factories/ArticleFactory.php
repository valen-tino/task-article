<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->text(20),
            'content'=>$this->faker->text(300),
            'image'=>$this->faker->text(20).'jpg'
        ];
    }
}
