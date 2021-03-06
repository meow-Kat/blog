<?php

namespace Tests\Feature;

use App\Models\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
// 使用這個套件授權驗證登入，要引入
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartItemsControllerTest extends TestCase
{
    use RefreshDatabase;

    private $fakeUser;

    // 測試一定要是登入狀態
    protected function setup(): void 
    {
        // TestCase 本身會執行一些工作，不打斷他，並執行要執行的動作
        parent::setUp();
        $this->fakeUser = User::create([
            'name' => 'Kat',
            'email' => 'meow@gmail.com',
            'password' => 123456789,
        ]);
        // 表現得像
        Passport::actingAs($this->fakeUser);
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $cart = Cart::factory()->create([
            // 切割必要資料
            'user_id' => $this->fakeUser->id
        ]);
        $product = Product::factory()->create();
        // 這樣可以結構化打過去的資料
        $response = $this->call(
            'POST',
            'cart-items',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
            ]
        );

        // 直接設定執行是成功的
        $response->assertOk();

        // 補在這邊
        $product = Product::factory()->less()->create();
        $response = $this->call(
            'POST',
            'cart-items',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 10,
            ]
        );
        // 對照 CartItemsController 數量不足的寫法
        $this->assertEquals($product->title.'數量不足' , $response->getContent());


        $response = $this->call(
            'POST',
            'cart-items',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 99999,
            ]
        );
        // 執行如果是 400 會通過
        $response->assertStatus(400);
    }

    public function testUpdate()
    {
        // 原本
        // $cart = Cart::factory()->create([
        //     // 切割必要資料
        //     'user_id' => $this->fakeUser->id
        // ]);
        // $product = Product::factory()->create();
        $cartItem = CartItem::factory()->create();
        $response = $this->call(
            'PUT',
            'cart-items/'.$cartItem->id,
            [ 'quantity' => 1 ]
        );
        // assertEquals() 前者 ( 期待 ) 和後者 ( 回傳的 json 資料 ) 一樣
        $this->assertEquals('true', $response->getContent());

        // refresh() 重新更新資料
        $cartItem->refresh();

        $this->assertEquals(1 , $cartItem->quantity);
    }

    public function testDestroy()
    {
        $cart = $this->fakeUser->carts()->create();
        $product = Product::factory()->make();
        $cartItem = $cart->cartItems()->create([
            'product_id' => $product->id,
            'quantity' => 10
        ]);
        $response = $this->call(
            'DELETE',
            'cart-items/'.$cartItem->id,
            [ 'quantity' => 1 ]
        );
        $response->assertOk();
        $cartItem = CartItem::find($cartItem->id);
        // assertNull 期待不被找到
        $this->assertNull($cartItem);
    }
}
