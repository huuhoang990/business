<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;

class GetDetailProductRequest extends FormRequest
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
            'product_id' => 'required|string|exists:products,product_id',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Vui lòng nhập mã sản phẩm',
            'product_id.string' => 'Mã sản phẩm phải là một chuỗi',
            'product_id.exists' => 'Mã sản phẩm không tồn tại trong hệ thống',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
