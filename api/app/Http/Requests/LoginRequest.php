<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseHelper;

class LoginRequest extends FormRequest
{
    use ResponseHelper;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
