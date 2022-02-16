<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    // 讓所有欄位都可加資料比較方便
    protected $guarded = [''];

    // 設一個 Attribute
    private $rate = 1;


    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    // 增加
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    // 製作函示
    public function checkout()
    {
        // 過很久才下單要確認庫存數量
        foreach($this->cartItems as $cartItem){
            $product = $cartItem->product;
            if(!$product->checkQuantity($cartItem->quantity)){
                // 只要執行到 return 就不會跑下面的程式
                return $product->title.'數量不足';
            }
        }

        $order = $this->order()->create([
            'user_id' => $this->user_id
        ]);
        // 判斷是不是 VIP 是就打折 0.8
        if($this->user->level == 2){ $this->rate = 0.8; }
        // 把每個購物車 item 轉成 Order Item
        foreach($this->cartItems as $cartItem){
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'pride' => $cartItem->product->price,
            ]);
            // 這邊的數量就是產品總數 - 購物車 cartItem 對應的數量
            $cartItem->product->update(['quantity' => $cartItem->product->quantity - $cartItem->quantity]);
        }
        $this->update(['checkouted' => true]);
        $order->orderItems;
        return $order;
    }
}
