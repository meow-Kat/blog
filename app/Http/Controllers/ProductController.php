<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   // 獲取 data   ↓ 這個在最下面
        // $data = $this->meow();
        $data = DB::table('product')->get();

        return response($data);
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
        $data = $this->meow();
        $newDate = $request->all();
        $data->push(collect( $newDate )) ;
        dd($data);
        return response($data);

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
        // 使用 collection 功能去做
        $from = $request->all();
        $data = $this->meow();
        // 找新增的資料 where 第一個是從哪個 key，第二個是 key 是什麼的資料，接著對第一筆更新
        $selectData = $data->where('id', $id)->first();
        // 更新
        $selectData = $selectData->merge(collect( $from) );
        return response($selectData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 刪除
        $data = $this->meow();
                                // 讓程式使用 $id ↓
        $data = $data->filter(function ($product) use ($id) {
            // 如果不等於 要刪除的ID 94說是要刪除的資料
            return $product['id'] != $id;
        });
        return response($data->values());
    }

    // 開發API
    public function meow()
    {
        return collect([
            collect([
                'id' => 0,
                'title' => 'Test 1',
                'content' => 'Good Product',
                'price' => '50',
            ]),
            collect([
                'id' => 1,
                'title' => 'Test 2',
                'content' => 'Great Product',
                'price' => '60',
            ]),
        ]);
    }
}
