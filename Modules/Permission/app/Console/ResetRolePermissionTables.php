<?php

namespace Modules\Permission\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResetRolePermissionTables extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reset:permission';

    /**
     * The console command description.
     */
    protected $description = 'Drop all roles and permissions and re-seed the tables with seeder.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dropping all roles and permissions...');

        $this->call('module:migrate-reset', [
            'module' => 'Permission',
        ]);

        $this->info('Roles and permissions dropped successfully.');

        if ($this->confirm('Do you also want to reset the admins table?', false)) {
            $this->dropAdminTable();
        }

        $this->info('Seeding roles and permissions...');

        $this->call('module:migrate', [
            'module' => 'Permission',
        ]);

        $this->call('module:seed', [
            'module' => 'Permission',
        ]);

        $this->call('db:seed', [
            '--class' => 'Modules\Admin\database\seeders\AdminDatabaseSeeder',
            '--force' => true,
        ]);

        $this->info('Roles and permissions seeded successfully.');
    }

    private function dropAdminTable()
    {
        $this->info('Dropping the admins table...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call('migrate:reset', [
            '--path' => 'Modules/Admin/database/migrations/2024_05_31_221853_create_admins_table.php',
            '--force' => true,
        ]);

        // Drop All Avata Images In Storage Admin Disk avatars Folder
        $avatars = Storage::disk('admin')->files('avatars');

        Storage::disk('admin')->delete($avatars);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Admins table dropped successfully.');

        $this->info('Re-creating the admins table...');

        $this->call('migrate', [
            '--path' => 'Modules/Admin/database/migrations/2024_05_31_221853_create_admins_table.php',
            '--force' => true,
        ]);

        $this->info('Admins table created successfully.');
    }
}
