<?php

namespace App\Actions\Fortify;

use App\Models\Nguoidung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

        /**
         * Validate and create a newly registered user.
         *
         * @param  array<string, string>  $input
         */
            public function create(array $input): Nguoidung
            {
                Validator::make($input, [
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique(Nguoidung::class)],
                'password' => $this->passwordRules(),
                'hoten' => ['required', 'string', 'max:255'],
                'gioitinh' => ['nullable', 'in:nam,nữ'],
            ])->validate();

            return Nguoidung::create([
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'hoten' => $input['hoten'],
                'gioitinh' => $input['gioitinh'] ?? 'nam',
                'vaitro' => 'user',
                'trangthai' => 'cho_duyet',
            ]);
    }
}
