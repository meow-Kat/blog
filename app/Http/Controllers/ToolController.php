<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Jobs\UpdateProductPriceduct;
use Illuminate\Http\Request;
// 引入 Redis
use Illuminate\Support\Facades\Redis;

class ToolController extends Controller
{
    //
    public function updateProductPrice()
    {
        $product = Product::all();
        foreach ($product as $product) {
            UpdateProductPriceduct::dispatch($product)->onQuene('tool');
        }
    }

    public function createProductRedis()
    {   // 使用 json 格式存進去
        Redis::set('product', json_encode(Product::all()));
    }
}
