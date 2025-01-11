<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierContactPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "transid" => '',
            'position_code' => "POS",
            'position_desc' => $this->faker->jobTitle(),
        ];
    }
}
