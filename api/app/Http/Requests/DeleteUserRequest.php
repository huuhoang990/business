<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;

class DeleteUserRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID người dùng không được bỏ trống',
            'user_id.integer' => 'ID người dùng phải là một số nguyên hợp lệ',
            'user_id.exists' => 'Người dùng không tồn tại',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
