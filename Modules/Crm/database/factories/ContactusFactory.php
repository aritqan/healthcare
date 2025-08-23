<?php

namespace Modules\Crm\database\factories;

use Modules\Crm\Enums\ContactusStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Crm\Models\Contactus::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(ContactusStatuses::all());
        $reply = $status === (ContactusStatuses::REPLIED) ? $this->faker->text : null;

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'message' => $this->faker->text,
            'reply' => $reply,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'status' => $status,
            'locale' => $this->faker->locale,
        ];
    }
}
