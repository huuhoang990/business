<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\GroupRole;
use App\Traits\ResponseHelper;

class AddUserRequest extends FormRequest
{
    use ResponseHelper;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5',
            'email' => 'required|string|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
            'group_role' => ['required', 'integer', Rule::enum(GroupRole::class)],
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên người sử dụng',
            'name.min' => 'Tên phải lớn hơn 5 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký.',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Mật khẩu ít nhất 5 ký tự',
            'password.regex' => 'Mật khẩu không bảo mật',
            'group_role.required' => 'Vai trò nhóm không được bỏ trống',
            'group_role.integer' => 'Vai trò nhóm phải là một số nguyên hợp lệ',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
