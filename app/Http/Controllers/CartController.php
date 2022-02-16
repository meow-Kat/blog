<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 修改
        
        // 透過這樣函式可以拿到通過驗證的 user 的資料
        $user = auth()->user();

        // firstOrcreate() 判斷 table 裡面有沒有資料如果沒有就新增
        // with(['']) 的作用，根據裡面的字串尋找 Model 內對應關聯，順便撈出來

        // 檢查 沒有這個人的購物車才要增加，如果有就使用這個人的購物車，沒找到資料就會 create
	    $cart = Cart::with(['cartItems'])->where('user_id', $user->id)->where('checkouted', false) ->firstorCreate(['user_id' => $user->id ]);

        // $cart = DB::table('carts')->get()->first();
        // // 判斷空值
        // if(empty($cart)){
        //     DB::table('carts')->insert(['created_at' => now() , 'updated_at' => now()]);
        //     $cart = DB::table('carts')->get()->first();
        // }
        // $cartItems = DB::table('cart_items')->where('cart_id', $cart->id)->get();
        // $cart = collect($cart);
        // $cart['items'] = collect($cartItems);

        // return response($cart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // 新增結帳的流程
    public function checkout()
    {
        // 誰要結帳
        $user = auth()->user();                          // 使用 model 取資料的 function，這樣使用 width 比較省效能
        $cart = $user->carts()->where('checkouted', false)->with('cartItems')->first();
        // 如果有撈到 $cart
        if($cart){
            $result = $cart->checkout();
            return response($result);
        }else{
            return response('沒有購物車', 400);
        }
    }
}
