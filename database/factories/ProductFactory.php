<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    // 有設定 model 這邊會自動帶入
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit,
            'title' => '測試產品',
            'content' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(10, 100)
        ];
    }

    // 產品數量不能不足，設定預設邏輯，當要建立一個 less 狀態的時候，上面函式的 quantity 會被下面取代
    public function less()
    {
        // 定義 factory 狀態，如果少的時候做的事情
        return $this->state(function (array $attribute){
            return[
                'quantity' => 1
            ];
        });
    }
}
