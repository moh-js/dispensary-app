<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
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
                'name' => 'Medicine',
            ], [
                'name' => 'Injection',
            ], [
                'name' => 'Medical Supply',
            ]
        ];

        foreach ($categories as $category) {
           InventoryCategory::firstOrCreate($category);
        }
    }
}
