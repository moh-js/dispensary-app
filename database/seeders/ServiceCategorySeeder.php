<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Medicine and Consumables',
            ], [
                'name' => 'Diagnostic Examination',
            ], [
                'name' => 'Procedure',
            ], [
                'name' => 'Registration or Consultation',
            ]
        ];

        foreach ($categories as $category) {
           ServiceCategory::firstOrCreate($category);
        }
    }
}
