<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    // 結帳後數量會減少，數量要被更新 Product Model，要保護那個欄位
    protected $guarded =[''];

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    // 檢查數量有沒有小於購買量
    public function checkQuantity($quantity)
    {
        if ($this->quantity < $quantity) {
            return false;
        }
        return true;
    }

    public function favorite_user()
    {   // 跟 user 的關係, 中間表格，因為建 table 有設定好外建是誰所以就可以不需要寫後面的兩個參數
        return $this->belongsToMany(User::class, 'favorites');
    }
}
