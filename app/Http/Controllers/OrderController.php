<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderDelivery;

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
}
