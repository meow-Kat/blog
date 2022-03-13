<?php

namespace App\Http\Services;
// 這邊會把 laravel 應用程式轉成 Client
use Guzzle\Client;
use Illuminate\Support\Facades\Log;

// 建立程式
class ShortUrlService
{
    protected $client;
    public function __construct()
    {
        // 建立一個套件
        $this->client = new Client();
    }
    public function makeShortUrl($url)
    {
        
        try {
            $accesstoken = '20f07f91f3303b2f66ab6f61698d977d69b83d64';
            $data = [
                'url' => $url
            ];
            // Log::info() 通用的 也有 Log::error
            Log::info('postData', ['data' => $data]); // 船過去的資料紀錄
            $response = $this->client->request(
                'POST',
                "https://api.pics.ee/v1/links/?access_token=$accesstoken",
                [
                    // json 格式
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => json_encode($data)
                ]
            );
            // getBody() 可以拿到所有回傳資料，getContents() 拿到乾淨的內容
            $contents = $response->getBody()->getContents();
            // 使用 channel() 指定的紀錄檔
            Log::channel('url_shorten')->info('responseData', ['data' => $contents]); // 回傳的資料紀錄
            // 回傳後解析
            $contents = $contents->json_decode($contents);
            return $contents->data->picseeUrl;
        } catch (\Throwable $th) {
            // 使用 report() 就會出現錯誤資訊
            report($th);
            // 如果沒辦法使用縮網址 就先回傳之前完整網址
            return $url;
        }
        
    }
}