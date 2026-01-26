<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
        ]);

         $currentDatabase = DB::getDatabaseName();

        if ($currentDatabase === 'society_management') {
            $this->call(SuperAdminSeeder::class);
        }
    }
}
