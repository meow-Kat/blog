<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\User;
// 讓這隻可以使用 artisan
use illuminate\Support\Facades\Artisan;
// 測試的關掉的部分要被支援所以引入
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class ExampleTest extends DuskTestCase
{
    // 洗掉 migration，也會更新一次並且 rollback
    use DatabaseMigrations;
    /**
     * A basic browser test example.
     *
     * @return void
     */

    protected function setup():void
    {
        parent::setUp();
        User::factory()->create([
            'email' => 'john@gmail.com',
        ]);
        // 這邊就會產假資料
        Artisan::call('db:seed',['--class' => 'ProductSeeder']);
    }

    // 這邊是複製 DuskTestCase 內的函式，這邊我們希望可以看到再幹嘛而不是背景執行，並可以自己關掉
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            # 關掉 gpu
            // '--disable-gpu'
            # 關掉 headless
            // '--headless'
        ]);
        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }


    public function testBasicExample()
    {
        // 開瀏覽器
        $this->browse(function (Browser $browser) {
            $browser->visit('/')  
                    ->with('.special-text', function($text){
                        $text->assertSee('固定資料');
                    });
            // 測試彈出視窗
            $browser->click('.check_product')
                    // 等 5 秒 ( 5 秒內有彈出都會執行下去 )
                    ->waitForDialog(5)
                    // 確認有跑出商品數量充足文字，前提是要先寫，我這邊沒做前端所以參考用
                    ->assertDialogOpened('商品數量充足')
                    // 按下確定
                    ->acceptDialog();
            // 看到執行後長怎樣
            // 讀到這行會暫停，可以執行 php 指令或擷取當下建立的變數、陣列...等
            eval(\Psy\sh());
        });
    }
    
    // 填表單
    public function testFillFrom()
    {
        // 開瀏覽器
        $this->browse(function (Browser $browser) {
            $browser->visit('contact-us')  
                    ->value('[name="name"]', 'cool')
                    ->select('[name="product"]', '食物')
                    // 點擊送出
                    ->press('送出')
                    ->assertQueryString('product', '食物');

            eval(\Psy\sh());
        });
    }
}
