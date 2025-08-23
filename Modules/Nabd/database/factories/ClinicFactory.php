<?php

namespace Modules\Nabd\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Nabd\Models\Clinic::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

