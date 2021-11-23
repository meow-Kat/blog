<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductSeeder::class);
        $this->command->info('產生固定 product 資料');
    }
}
