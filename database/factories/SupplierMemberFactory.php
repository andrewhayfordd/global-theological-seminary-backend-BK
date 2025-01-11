<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transid' => '',
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
