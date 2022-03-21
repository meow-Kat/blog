<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $dataCount;

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
        $this->dataCount = $orders->count();
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
            // exl 的 E 欄位，日期格式
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                // sheet 代表這個表格，如果是多個檔案需要放在 Sheets 資料夾內，目前只需要單個頁面所以先放這邊
                // getDelegate() 是只有引入 PhpSpreadsheet 套件才能執行的函式
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(500); // 這樣就會比較寬
                for ($i=0; $i < $this->dataCount; $i++) { 
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50); // 這樣就會比較高
                }
                // getStyle('A1:B'.$this->dataCount) 這定範圍                     置中
                $event->sheet->getDelegate()->getStyle('A1:B'.$this->dataCount)->getAlignment()->setVertical('center');
                // 陣列的方式寫全部就不用一行行寫
                $event->sheet->getDelegate()->getStyle('A1:A'.$this->dataCount)->applyFromArray([
                    'font' => [
                        // 字形
                        'name' => 'Arial',
                        'blod' => true,
                        'italic' =>true,
                        'color' => [
                            'rgb' => 'FF0000'
                        ]
                    ],
                    'fill' =>[
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '000000'
                        ],
                        'endtColor' => [
                            'rgb' => '000000'
                        ],
                    ]
                ]);
                // 合併儲存格
                $event->sheet->getDelegate()->mergeCells('G1:H1');
            }
        ];
    }
}
