<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthOrderCodeRequesty extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true; // Cho phép validate và chạy tiếp
    }


    public function rules(): array
    {
        return [
            'madon' => [
                'required',
                'string',
                'regex:/^VNA[0-9]{7}$/'
            ],
        ];


    }

}
