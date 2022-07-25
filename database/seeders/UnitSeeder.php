<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            [
                'name' => 'Dispensing',
            ], [
                'name' => 'Lab',
            ], [
                'name' => 'RCH',
            ], [
                'name' => 'OPD',
            ], [
                'name' => 'Injection',
            ], [
                'name' => 'Store',
            ]
        ];

        foreach ($units as $unit) {
           Unit::firstOrCreate($unit);
        }
    }
}
