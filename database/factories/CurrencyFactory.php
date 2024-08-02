<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => "REAL",
            'number' => 171,
            'decimal' => 2,
            'currency_name' => "Real",
            'currency_locations' => "Patria Amada Brasil"
        ];
    }
}
