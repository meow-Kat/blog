<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;

    use SoftDeletes; // 新增這行

    // 白名單功能
    protected $fillable = [];
    // 黑名單功能
    protected $guarded = [''];
    // 不想給別人看的資料可以這樣
    // protected $hidden = ['updated_at']; 因為使用了 Model 所以這個欄位不用隱藏了
    // 設定自製屬性名稱
    protected $appends = ['current_price'];
            // 當 Model 呼叫 ↑ 屬性的時候，會執行下面的函式
    public function getCurrentPriceAttribut()
    {
        // 這個 Model 本身的 ↓
        return $this->quantity * 10;
    }

    // 一對多的一 單數 不加 s
    public function product()
    {   // 系統會自己找 Product_id 自己對應到 Product
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {   // 系統會自己找 Cart_id 自己對應到 Product
        return $this->belongsTo(Cart::class);
    }
}
