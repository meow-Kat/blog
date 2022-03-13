<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogErrors extends Model
{
    use HasFactory;

    protected $guarded = [''];
    // 屬性被處理時的資料類型
    protected $casts = [
        // trace 是存 json，這樣設定是惠存陣列轉 json 進去，取出時會從 json 變回陣列
        'trace' => 'array',
        'params' => 'array',
        'header' => 'array'
    ];
}
