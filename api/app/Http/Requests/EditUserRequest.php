<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\GroupRole;
use Illuminate\Validation\Rule;
use App\Traits\ResponseHelper;

class EditUserRequest extends FormRequest
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
        $userId = $this->input('user_id') ?? $this->route('user')->id ?? null;
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id'),
            ],
            'password' => [
                Rule::when(!empty($this->password), ['string', 'min:6']),
            ],
            'group_role' => ['required', 'integer', Rule::enum(GroupRole::class)],
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID người dùng không được bỏ trống',
            'user_id.integer' => 'ID người dùng phải là một số nguyên hợp lệ',
            'user_id.exists' => 'Người dùng không tồn tại',
            'name.required' => 'Tên không được bỏ trống',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký.',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
            'group_role.required' => 'Vai trò nhóm không được bỏ trống',
            'group_role.integer' => 'Vai trò nhóm phải là một số nguyên hợp lệ',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
