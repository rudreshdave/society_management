<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'superadmin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Owner', 'slug' => 'owner'],
            ['name' => 'Tenant', 'slug' => 'tenant'],
            ['name' => 'Staff', 'slug' => 'staff'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
