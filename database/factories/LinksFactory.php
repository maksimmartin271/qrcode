<?php

use App\Models\links;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinksFactory extends Factory
{
    protected $model = links::class;

    public function definition()
    {
        return [
            'url_to' => $this->faker->url,
            'url_from' => $this->faker->unique()->slug,
        ];
    }
}
