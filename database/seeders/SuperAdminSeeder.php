<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'superadmin@yopmail.com'],
            [
                'name'     => 'Super Admin',
                'email' => 'superadmin@yopmail.com',
                'mobile' => '9999999999',
                'password' => Hash::make('Superadmin@1234'),
                'status'   => 1,
            ]
        );

        $superAdminRole = Role::where('slug', 'superadmin')->first();

        if ($superAdminRole) {
            // Attach ONLY role_id (no wing_id / house_id)
            $user->roles()->syncWithoutDetaching([
                $superAdminRole->id
            ]);
        }
    }
}
