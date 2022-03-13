<?php

namespace App\Exceptions;

use App\Models\LogErrors;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
// 引入驗秤錯誤時候的套件
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $user = auth()->user();
            LogErrors::create([
                'user_id' => $user ? $user->id : 0,
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'line' => $e->getline(),
                // 會把全部參數放進來太多，使用 array_map 查找
                'trace' => array_map( function($trace){
                    // 過濾參數
                    unset($trace['args']);
                    return $trace;
                }, $e->getTrace() ),
                'method' => request()->getMethod(),
                'params' => request()->all(),
                'uri' => request()->getPathInfo(),
                'user_agent' => request()->userAgent(),
                'header' => request()->headers->all(),
            ]);
        });
        // 客製錯誤畫面
        $this->renderable(function(Throwable $e){
            // 回傳錯誤畫面
            return response()->view('error');
        });
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        // 發現有錯誤的時候
        return response('授權失敗', 401);
    }
}
