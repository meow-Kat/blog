<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class OrderExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 製作符合 exl 格式資料
        $orders = Order::with(['user','cart.cartItems.product'])->get();
        // 使用 map() 重組陣列
        $orders = $orders->map(function($order){
            return [
                $order->id,
                $order->user->name,
                $order->is_shipped,
                $order->cart->cartItems->sum(function($cartItem){
                    return $cartItem->product->price * $cartItem->quantity;
                }),
                // 這邊使用組件的功能才能得到 exl 格式
                Date::dateTimeToExcel($order->created_at)
            ];
        });
        return $orders;
    }

    public function headings(): array
    {   // 拿到表單的標題
        return ['編號', '購買者', '總價', '總價', '購買時間'];
    }

    // 用 WithColumnFormatting 組件並且使用 NumberFormat
    public function columnFormats(): array
    {
        // 回傳特定某一欄
        return [
            // exl 的 B 欄位，純文字格式
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
