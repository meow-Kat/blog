<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// 這邊
class CheckDirtyWord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 建立一個過濾單字的陣列
        $dirtyWords = [
            'apple','egg'
        ];
        $parameters = $request->all();
        foreach($parameters as $key => $value){
            if ($key == 'content') {
                foreach ($dirtyWords as $dirtyWord) {
                    // 看 content 有沒有包含 dirtyWords
                    if (strpos($value ,$dirtyWord) !== false) {
                        return response('dirty', 400);
                    }
                }
            }
        }
        return $next($request);
    }
}
