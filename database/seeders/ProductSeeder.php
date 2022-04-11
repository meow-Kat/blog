<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Product;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   // 帶陣列
        Product::upsert([
            // id 也能固定下來                                                  產生 0 ~ 300數字
            ['id' => '1','title' => '固定資料1','content' => '固定內容','price' => rand(0,300),'quantity' => 20],
            ['id' => '2','title' => '固定資料2','content' => '固定內容','price' => rand(0,300),'quantity' => 20]
                // ↓ 這兩個如果有改變就更新
        ],['id'],['price','quantity']);
        // ↑主鍵，id 找主鍵，沒有就更新，有建立

    }
}
