<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
// 使用這個套件授權驗證登入，要引入
use Laravel\Passport\Passport;
use App\Http\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private $fakeUser;

    // 測試一定要是登入狀態
    protected function setup(): void 
    {
        parent::setUP(); 
    }
    public function testSharedUrl()
    {
        $product = Product::factory()->create();
        $id = $product->id;
        // 使用 mock
        $this->mock(ShortUrlService::class , function($mock) use($id) {
            $mock->shouldReceive('makeShortUrl')
            ->with("http://localhost:3000/products/$id")
            ->andReturn('fakeUrl');
        });

        $this->mock(AuthService::class , function($mock)  {
            $mock->shouldReceive('fakeReturn');
        });

        $response = $this->call(
            'GET',
            'products/'.$id.'/shared-url'
        );

        // 判斷
        $response->assertOk();
        $res = json_decode($response->getContent(), true);
        $this->assertEquals($response['url'], 'fakeUrl');
    }
}