<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseHelper;

class SearchUserRequest extends FormRequest
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
            'name' => 'string|nullable|max:255',
            'email' => 'string|nullable|max:255',
            'group_role' => 'string|nullable|max:50',
            'is_active' => 'numeric|nullable|in:0,1',
            'page' => 'numeric|nullable|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Tên phải là một chuỗi hợp lệ',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.string' => 'Email phải là một chuỗi hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'group_role.string' => 'Vai trò nhóm phải là một chuỗi hợp lệ',
            'group_role.max' => 'Vai trò nhóm không được vượt quá 50 ký tự',
            'is_active.numeric' => 'Trạng thái hoạt động phải là một số',
            'is_active.in' => 'Trạng thái hoạt động chỉ có thể là 0 hoặc 1',
            'page.numeric' => 'Trang phải là một số hợp lệ',
            'page.min' => 'Trang phải lớn hơn hoặc bằng 1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
