<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;

class UpdateActiveUserRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID người dùng không được bỏ trống',
            'user_id.integer' => 'ID người dùng phải là một số nguyên hợp lệ',
            'user_id.exists' => 'Người dùng không tồn tại',
            'is_active.required' => 'Trạng thái hoạt động không được bỏ trống',
            'is_active.boolean' => 'Trạng thái hoạt động phải là true hoặc false',
            'is_active.in' => 'Trạng thái hoạt động phải là true hoặc false',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
