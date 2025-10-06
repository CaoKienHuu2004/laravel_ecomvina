<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Nếu là request API hoặc front-end muốn JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(401, 'Unauthorized'); // trả về JSON 401
        }
        // Nếu có UI web, bạn vẫn có thể redirect
        // return route('login');
        return $request->expectsJson() ? null : route('login');



    }
}
