<?php

namespace App\Services;

use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink(string $email)
    {
        // Gửi link reset đến email
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $credentials, callable $callback)
    {
        // Dùng Password::broker để reset password
        return Password::reset(
            $credentials,
            $callback
        );
    }
}


// namespace App\Services;

// use Illuminate\Support\Facades\Password;

// class PasswordResetService
// {
//     public function sendResetLink(array $credentials)
//     {
//         return Password::broker()->sendResetLink($credentials);
//     }

//     public function resetPassword(array $credentials, callable $callback)
//     {
//         return Password::broker()->reset($credentials, $callback);
//     }
// }
