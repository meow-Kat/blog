<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// 使用這個內建套件
use Illuminate\Support\Facades\Validator;
// 引進來
use App\Http\Requests\UpdateCartItem;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $message = [
            'required' => ':attribute 是必要的',
            'between' => ':attribute 的輸入 :input 不再 :min 和 :max 之間'
        ];
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required | integer | between:1,10',
            'product_id' => 'required | integer | between:1,10',
            'quantity' => 'required | integer | between:1,10',
        ],$message);
        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        // 通過驗證了後
        $validatedData = $validator->validate();
        DB::table('cart_items')->insert([
            'cart_id' => $validatedData['cart_id'] ,
            'product_id' => $validatedData['product_id'] ,
            'quantity' => $validatedData['quantity'] ,
            'created_at' => now() ,
            'updated_at' => now(),
        ]);
        // 用這個方式包 資料庫才會存 true
        return response()->json(true);
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
    public function update(UpdateCartItem $request, $id)
    {
        $form = $request->validated();
        // 更新語法用 update ，找出更新的資料
        DB::table('cart_items')->where('id', $id)->update([
            'quantity' => $form['quantity'] ,
            'updated_at' => now(),
        ]);
        // 用這個方式包 資料庫才會存 true
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 更新語法用 update ，找出更新的資料
        DB::table('cart_items')->where('id', $id)->delete();
        return response()->json(true);
    }
}
