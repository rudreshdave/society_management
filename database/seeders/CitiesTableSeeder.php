<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch states as: ['STATE NAME' => id]
        $states = DB::table('states')
            ->select('id', 'name')
            ->get()
            ->mapWithKeys(function ($state) {
                return [
                    Str::upper(trim($state->name)) => $state->id
                ];
            });

        $filePath = storage_path('app/cities_data.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error('cities_data.xlsx file not found.');
            return;
        }

        $rows = Excel::toCollection(collect(), $filePath)->first();

        $rows->each(function ($row, $index) use ($states) {

            // Skip header row
            if ($index === 0) {
                return;
            }

            $stateName = isset($row[0]) ? Str::upper(trim($row[0])) : null;
            $cityName  = isset($row[1]) ? trim($row[1]) : null;

            if (empty($stateName) || empty($cityName)) {
                return;
            }

            if (!isset($states[$stateName])) {
                $this->command->warn("State not found in DB: {$stateName}");
                return;
            }

            DB::table('cities')->updateOrInsert(
                [
                    'state_id' => $states[$stateName],
                    'name'     => $cityName,
                ],
                [
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        });
    }
}
