<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = [
            ['name' => 'main', 'db_name' => 'dispensary_app'],
            ['name' => 'rukwa', 'db_name' => 'mrcc_dispensary_app']
        ];

        foreach ($stations as $station) {
            Station::firstOrCreate($station);
        }
    }
}
