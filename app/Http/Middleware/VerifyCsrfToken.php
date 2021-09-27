<?php
// Middleware 檢查密碼合不合法
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 先讓所有的 middleware 不用去管合不合法
        '*' // * = 所有
    ];
}
