<?php

namespace Modules\Admin\database\factories;

use Illuminate\Support\Str;
use Modules\Base\Enums\Gender;
use Modules\Admin\Models\Admin;
use Silber\Bouncer\BouncerFacade;
use Modules\Permission\Models\Role;
use PHPUnit\Event\Telemetry\System;
use Modules\Admin\Enums\AdminStatus;
use Modules\Permission\Enums\SystemDefaultRoles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Admin\Models\Admin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $gender          = $this->faker->randomElement(Gender::all());

        return [
            'status'            => $this->faker->randomElement(AdminStatus::all()),
            'full_name'         => $this->faker->name($gender),
            'username'          => $this->faker->unique()->userName,
            'phone_number'      => $this->faker->phoneNumber,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'lang'              => $this->faker->randomElement(LaravelLocalization::getSupportedLanguagesKeys()),
            'gender'            => $gender,
            'ip_address'        => $this->faker->ipv4,
            'remember_token'    => Str::random(10),
            'last_login_at'     => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Admin $admin) {
            $randomImagePath = asset('modules/admin/metronic/demo/media/avatars/300-'. rand(1, 30) .'.jpg');
            $admin->addMediaFromUrl($randomImagePath)->toMediaCollection(Admin::MEDIA_COLLECTION);

            $role = Role::inRandomOrder()->first();
            BouncerFacade::assign($role)->to($admin);
        });
    }
}

