<?php

namespace App\Actions\Fortify;

use App\Models\Nguoidung; // đổi từ User sang Nguoidung
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(Nguoidung $user, array $input): void
    {
        Validator::make($input, [
            'hoten' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('nguoi_dung')->ignore($user->id),
            ],
            'gioitinh' => ['nullable', 'in:nam,nữ'],
            'ngaysinh' => ['nullable', 'date'],
            'sodienthoai' => ['nullable', 'string', 'max:15', Rule::unique('nguoi_dung')->ignore($user->id)],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'hoten' => $input['hoten'],
                'email' => $input['email'],
                'gioitinh' => $input['gioitinh'] ?? 'nam',
                'ngaysinh' => $input['ngaysinh'] ?? null,
                'sodienthoai' => $input['sodienthoai'] ?? null,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(Nguoidung $user, array $input): void
    {
        $user->forceFill([
            'hoten' => $input['hoten'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'gioitinh' => $input['gioitinh'] ?? 'nam',
            'ngaysinh' => $input['ngaysinh'] ?? null,
            'sodienthoai' => $input['sodienthoai'] ?? null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
