<?php

namespace Modules\Blog\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Magazine\Entities\Magazine;
use Modules\Permission\Entities\Permission;
use Modules\User\Entities\User;

class BlogFactory extends Factory
{
    protected $model = \Modules\Blog\Entities\Blog::class;


    public function definition(): array
    {
        return [
            "title" => $this->faker->sentence,
            "subtitle" => $this->faker->sentence,
            "summary" => $this->faker->sentence(30),
            "content" => $this->faker->realText(6000),
            "tags" => $this->faker->words(6),
            "magazine_id" => Magazine::inRandomOrder()->first()->id,
            "author_id" => User::where("role", "admin")->first()->id,
            "created_at" => $this->faker->dateTime(),
            "updated_at" => $this->faker->dateTime(),
            "photo_id" => $this->faker->randomElement([11,12,13]),
        ];
    }
}

