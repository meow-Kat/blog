<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
// 製作一個
use Illuminate\Support\Facades\Redis;
// 引入
use App\Http\Services\ShortUrlService;
use Illuminate\Support\Facades\Storage;
// 引入測試的 Services
use App\Http\Services\AuthService;

class ProductController extends Controller
{
    // 建立 construct 依賴注入
    public function __construct(ShortUrlService $shortUrlService, AuthService $authService)
    {
        $this->$shortUrlService = $shortUrlService;
        $this->$authService = $authService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   // 獲取 data   ↓ 這個在最下面
        // $data = $this->meow();
        // $data = DB::table('product')->get();
        // 取出來要轉碼
        $data = json_decode(Redis::get('product'));

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

    
    public function sharedUrl($id)
    {
        $this->authService->fakeReturn();
        // 使用依賴注入這邊不需要了
        // $service = new ShortUrlService();
        // 這邊要使用雙引號才能作用
        $url = $this->shortUrlService->makeShortUrl("http://localhost:3000/products/$id");
        return response(['url' => $url]);
    }

    public function upLoadImage(Request $request)
    {
        //  拿到圖片
        $file = $request->file('product_image');
        $productId = $request->input('product_id');
        // 如果沒圖片就回上一頁
        if(is_null($productId)){
            // 顯示前端參數錯誤
            return redirect()->back()->withErrors(['msg' => '參數錯誤']);
        }
        $product = Product::find($productId);
        // 這樣就存好了
        $path = $file->store('images'); // 這邊會回傳路徑
        $product->images()->create([
            // 能拿到使用者上傳的黨名
            'filename' => $file->getClientOriginalName(),
            'path' => $path
        ]);
        return redirect()->back();
    }

    public function getImageUrlAttribute()
    {
        $images = $this->images;
        if($images->isNotEmpty()){
            // Storage 內建套件去對儲存空間操作
            // $images->last()->path 把路徑放進去產生指定資料夾位置
            return Storage::url($images->last()->path);
        }
    }

    public function import(Request $request)
    {
        $file = $request->file('excel');
        Excel::import(new ProductsImport, $file);
	    return redirect()->back();
    }
}
