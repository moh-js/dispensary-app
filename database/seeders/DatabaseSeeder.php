<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(InventoryCategorySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ServiceCategorySeeder::class);
    }
}
