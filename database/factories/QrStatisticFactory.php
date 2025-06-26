<?php

namespace Database\Factories;

use App\Models\QrStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

class QrStatisticFactory extends Factory
{
    protected $model = QrStatistic::class;

    public function definition()
    {
        return [
            'qr_code_id' => function () {
                return \App\Models\QrCode::factory()->create()->id;
            },
            'type' => $this->faker->randomElement(['url', 'text', 'phone', 'email']),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
        ];
    }
}
