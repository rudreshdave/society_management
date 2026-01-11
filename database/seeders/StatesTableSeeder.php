<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $states = [
            ['name' => 'Andhra Pradesh', 'code' => 'AP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arunachal Pradesh', 'code' => 'AR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Assam', 'code' => 'AS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bihar', 'code' => 'BR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chhattisgarh', 'code' => 'CG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Goa', 'code' => 'GA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gujarat', 'code' => 'GJ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Haryana', 'code' => 'HR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Himachal Pradesh', 'code' => 'HP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jharkhand', 'code' => 'JH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Karnataka', 'code' => 'KA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kerala', 'code' => 'KL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Madhya Pradesh', 'code' => 'MP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Maharashtra', 'code' => 'MH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manipur', 'code' => 'MN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Meghalaya', 'code' => 'ML', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mizoram', 'code' => 'MZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nagaland', 'code' => 'NL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Odisha', 'code' => 'OD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Punjab', 'code' => 'PB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rajasthan', 'code' => 'RJ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sikkim', 'code' => 'SK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tamil Nadu', 'code' => 'TN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Telangana', 'code' => 'TS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tripura', 'code' => 'TR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Uttar Pradesh', 'code' => 'UP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Uttarakhand', 'code' => 'UK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'West Bengal', 'code' => 'WB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delhi', 'code' => 'DL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Andaman & Nicobar Islands', 'code' => 'AN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jammu & Kashmir', 'code' => 'JK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chandigarh', 'code' => 'CH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dadra & Nagar Haveli', 'code' => 'DH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Daman And Diu', 'code' => 'DD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lakshadweep', 'code' => 'LD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Puducherry', 'code' => 'PY', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('states')->upsert(
            $states,
            ['code'],
            ['name', 'updated_at']
        );
    }
}
