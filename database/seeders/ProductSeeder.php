<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        Product::factory()
            ->count(50)
            ->create();
    }
}
