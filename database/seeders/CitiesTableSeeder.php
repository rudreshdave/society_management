<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = DB::table('states')
            ->select('id', 'name')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Str::upper(trim($item->name)) => $item->id];
            });

        $filePath = storage_path('app/cities_data.xlsx');

        $data = Excel::toCollection(collect(), $filePath)->first();

        $data->each(function ($row) use ($states) {
            $stateName = $row[0]; // Adjust index based on your Excel column
            $cityName  = $row[1];

            if (empty($stateName) || empty($cityName) || $stateName === 'State') {
                return;
            }

            if (isset($states[$stateName])) {
                // updateOrInsert(unique_constraints, values_to_update)
                DB::table('cities')->updateOrInsert(
                    [
                        'state_id' => $states[$stateName],
                        'name'     => $cityName
                    ],
                    [
                        'updated_at' => Carbon::now()
                    ]
                );
            } else {
                $this->command->warn("State not found in DB: {$stateName}");
            }
        });
    }
}
