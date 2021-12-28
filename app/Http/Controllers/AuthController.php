<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Models\User;
// 引入
// 解析 user 產生通行證 ( token ) 轉交 client 端 ( 也就是打 API 的人 )，拿到每個 API 都要看 token
use illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function signup(CreateUser $request)
    {
        $validateData = $request->validated();
        // 使用 Model 呼叫
        $user = new User([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
                        // ↓ 加密的函示 把原本的密碼加密
            'password' => bcrypt($validateData['password']),
        ]);
        $user->save();
                                // ↓ 建立資料狀態碼
        return response('success',201);
    }

    // 增加 login 功能
    public function login(Request $request)
    {
        $validatedDate = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        // 如果登入失敗 (這邊要先做) attempt：做登入動作
        if(!Auth::attempt($validatedDate)){
            return response('授權失敗', 401);
        }
        // 如果授權成功，可以撈到 user 資料
        $user = $request->user();
        // 成功後要建立通行證
        $tokenResult = $user->createToken('Token');
        // 這邊如果是 laravel 8.12 以上會有問題，需要在 terminal 下指令 composer require lcobucci/jwt:3.3.3
        // 這邊就會建立好 token
        dd($tokenResult);
        $tokenResult->token->save;
        return response(['token' => $tokenResult->accessToken]);
        // 這邊產生的 token 亂碼是 JWT 產出來的，這邊會存在資料庫上的 Auth 的 access_token 的資料表
    }

    // 製作登出
    public function logout(Request $request)
    {   // 透過使用者叫出資料，呼叫 token() 拿到相關資訊，執行 revoke() ( 讓 token 失效，在資料庫會被標記 revoked 為 1 )
        $request->user()->token()->revoke();
        // 執行成功後回傳
        return response(
            ['message' => '成功登出']
        );
    }


    // 製作會被保護的 api 端點，取得 user 自己資料
    public function user(Request $request)
    {
        return response(
            // 當通過檢查後，會把資料塞進去 user，並透過 user() 取得資料
            $request->user()
        );
    }
}
