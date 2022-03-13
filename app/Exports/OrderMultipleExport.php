<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\OrderByShippedSheet;

// 資料不是在 DB collection 組合成，而是在活頁組合
class OrderMultipleExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheet(): array
    {
        $sheets = [];
        // 這樣就會自己組合
        foreach ([true, false] as $isShipped) {
            // 做一個活頁的檔案
            $sheets[] = new OrderByShippedSheet($isShipped);
        }
        return  $sheets;
    }
}
