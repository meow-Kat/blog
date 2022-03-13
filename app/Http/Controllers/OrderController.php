<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Exports\OrderMultipleExport;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use App\Notifications\OrderDelivery;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    //
    public function delivery($id)
    {
        $order = Order::findOrFail($id);
        if ($order->is_shipped) {
            return response(['result' => false]);
        }else{
            $order->update(['result' => true]);
            // User 本身就有引入 notification，使用推撥函式 notify()，new OrderDelivery 需要引入要檢查一下
            $order->user->notify(new OrderDelivery);
            return response(['result' => true]);
        }
    }

    public function export()
    {   // 第二個參數是檔案名稱
        return Excel::download(new OrderExport, 'orders.xlsx');
    }

    public function exportByShipped()
    {   // 第二個參數是檔案名稱
        return Excel::download(new OrderMultipleExport, 'orders_by_shipped.xlsx');
    }
}
