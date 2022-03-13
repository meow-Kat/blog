<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;
// 引入WithTitle
use Maatwebsite\Excel\Concerns\WithTitle;

// 多引入 WithTitle 設定活頁名稱
class OrderByShippedSheet implements FromCollection, WithHeadings, WithTitle
{
    // 會傳參數近來
    public $isShipped;
    public function __construct($isShipped)
    {
        $this->isShipped = $isShipped;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::where('is_shipped' , $this->isShipped)->get();
    }

    public function headings(): array
    {   // 拿到表單的標題
        return Schema::getColumnListing('orders');
    }

    public function title(): string
    {
        return  $this->isShipped ? '已送' : '未送';
    }
}
