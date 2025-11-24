<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthUsernameOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true; // Cho phép validate và chạy tiếp
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * Phương thức này định nghĩa các quy tắc để xác thực dữ liệu khi người dùng tra cứu đơn hàng.
     * - 'username': Tên đăng nhập, không bắt buộc, tối thiểu 6 ký tự , tối đa 20 ký tự, chỉ chứa chữ cái, số và dấu gạch dưới.
     * - 'email': Địa chỉ email, không bắt buộc, tối đa 50 ký tự, phải là định dạng email hợp lệ.
     * - 'madon': Mã đơn hàng, bắt buộc, phải có định dạng bắt đầu bằng "VNA" và theo sau là 7 chữ số.
     *
     * Lưu ý: Yêu cầu phải cung cấp ít nhất một trong hai trường 'username' hoặc 'email'.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['nullable','string','min:6','max:20','regex:/^[A-Za-z0-9_]+$/'],

            'email' => [
                'required',
                'string',
                'email:rfc,dns,filter',   // kiểm tra format + DNS MX
                'max:50',
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',   // không khoảng trắng + phải có domain
            ],

            'madon' => [
                'required',
                'string',
                'regex:/^VNA[0-9]{7}$/'
            ], // custom condition: username hoặc email phải có ít nhất 1
        ];


    }
    /**
     * Chuẩn bị dữ liệu cho việc xác thực.
     *
     * Phương thức này được gọi trước khi các quy tắc xác thực được áp dụng.
     * Nó kiểm tra xem `username` và `email` có đồng thời bị thiếu trong yêu cầu hay không.
     * Nếu cả hai đều thiếu, nó sẽ thêm một trường `_missing_username_email` vào dữ liệu yêu cầu
     * để có thể sử dụng trong quy tắc xác thực, đảm bảo rằng người dùng phải cung cấp
     * ít nhất một trong hai thông tin này.
     */
    // Validate thêm logic: phải có username hoặc email
    protected function prepareForValidation()
    {
        if (!$this->username && !$this->email) {
            $this->merge(['_missing_username_email' => true]);
        }
    }

    /**
     * Cấu hình validator instance.
     *
     * Thêm một quy tắc xác thực tùy chỉnh sau khi các quy tắc cơ bản đã được chạy.
     * Cụ thể, phương thức này sẽ kiểm tra nếu thuộc tính `_missing_username_email` được đặt là true,
     * nó sẽ thêm một lỗi vào trường 'username' yêu cầu người dùng nhập username hoặc email.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->_missing_username_email ?? false) {
                $validator->errors()->add('username', 'Bạn phải nhập username hoặc email!');
            }
        });
    }
}
