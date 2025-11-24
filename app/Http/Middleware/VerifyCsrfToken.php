<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        // '/login', // này có fortify CSRF rồi
        // '/register',  // này có fortify CSRF rồi
        // '/reset-password',  // này có fortify CSRF rồi
        'api/*', // dung cho API token của santum
        'toi/*', // mai mốt FE cùng domain thì xóa đi
        'auth/*', // mai mốt FE cùng domain thì xóa đi
        // '*', // nguy hiểm, tắt CSRF hoàn toàn
        // 'toi/giohang',
    ];
}
