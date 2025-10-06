<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}

// namespace App\Actions\Fortify;

// use App\Models\Nguoidung; // đổi từ User sang Nguoidung
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Laravel\Fortify\Contracts\ResetsUserPasswords;

// class ResetUserPassword implements ResetsUserPasswords
// {
//     use PasswordValidationRules;

//     /**
//      * Validate and reset the user's forgotten password.
//      *
//      * @param  Nguoidung  $user
//      * @param  array<string, string>  $input
//      */
//     public function reset(Nguoidung $user, array $input): void
//     {
//         Validator::make($input, [
//             'password' => $this->passwordRules(),
//         ])->validate();

//         $user->forceFill([
//             'password' => Hash::make($input['password']),
//         ])->save();
//     }
// }
