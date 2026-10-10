<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Local-only login for the workbench panels.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Workbench User',
            'email' => 'workbench@example.test',
            'password' => 'workbench-local-only',
        ]);
    }
}
