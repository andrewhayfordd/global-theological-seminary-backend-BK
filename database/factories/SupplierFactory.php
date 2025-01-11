<?php

namespace Database\Factories;

use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
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
            "name" => $this->faker->name(),
            "phone" => $this->faker->phoneNumber(),
            "email" =>$this->faker->email(),
            "address" => $this->faker->address(),
        ];
    }
}
