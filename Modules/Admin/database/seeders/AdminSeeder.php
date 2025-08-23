<?php

namespace Modules\Admin\database\seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade;
use Modules\Base\Enums\Gender;
use Modules\Admin\Models\Admin;
use Modules\Permission\Enums\SystemDefaultRoles;

class AdminSeeder extends Seeder
{
    public $data = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedsystemAdmins();
    }

     /**
     * @var array
     */
    public static $systemAdmins = [
        SystemDefaultRoles::ROOT_ROLE => [
            'full_name' => 'Root',
            'username'  => 'root',
            'email'     => 'root@root.com',
            'password'  => 'root@1234',
            'gender'    => Gender::MALE,
        ],
        SystemDefaultRoles::SYSTEM_ADMIN_ROLE  => [
            'full_name' => 'System Admin',
            'username'  => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => 'admin@1234',
            'gender'    => Gender::MALE,
        ],
    ];

    /**
     * Create system users
     *
     * @param  array $user
     *
     * @return \Modules\Admin\Models\Admin
     */
    public function createDeafultAdmins(array $admin)
    {
        if (count($admin) == 0) return false;

        $this->data[$admin['username']] = Admin::firstOrCreate(
            [
                'email' => $admin['email']
            ],
            [
                'full_name'         => $admin['full_name'],
                'username'          => $admin['username'],
                'password'          => $admin['password'],
                'email_verified_at' => now()->toDateTimeString(),
                'lang'              => 'en',
                'gender'            => $admin['gender'],
            ]
        );

        return $this->data[$admin['username']];
    }

    /**
     * Add the users identified within this array (systemAdmins)
     */
    public function seedsystemAdmins()
    {
        foreach(self::$systemAdmins as $role => $user) {
            $createdUser = $this->createDeafultAdmins($user);
            $bouncerRole = BouncerFacade::role()->withoutGlobalScope('withoutRoot')->firstOrCreate(['name' => $role]);
            BouncerFacade::assign($bouncerRole)->to($createdUser);
        }
    }
}
