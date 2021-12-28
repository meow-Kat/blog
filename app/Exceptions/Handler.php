<?php

namespace App\Exceptions;

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
            //
        });
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        // 發現有錯誤的時候
        return response('授權失敗', 401);
    }
}
