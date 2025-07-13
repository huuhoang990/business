<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseHelper;

class SearchProductRequest extends FormRequest
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
            'product_name' => 'string|nullable|max:255',
            'is_sales' => 'numeric|nullable|in:0,1',
            'product_price_min' => 'numeric|nullable|min:0',
            'product_price_max' => 'numeric|nullable|gte:product_price_min',
            'page' => 'numeric|nullable|min:1',
        ];
    }

    public function messages()
    {
        return [
            'product_name.string' => 'Tên sản phẩm phải là một chuỗi',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'is_sales.numeric' => 'Trạng thái bán hàng phải là một số',
            'is_sales.in' => 'Trạng thái bán hàng phải là 0 hoặc 1',
            'product_price_from.numeric' => 'Giá sản phẩm từ phải là một số',
            'product_price_from.min' => 'Giá sản phẩm từ phải lớn hơn hoặc bằng 0',
            'product_price_to.numeric' => 'Giá sản phẩm đến phải là một số',
            'product_price_to.gte' => 'Giá sản phẩm đến phải lớn hơn hoặc bằng giá sản phẩm từ',
            'page.numeric' => 'Trang phải là một số',
            'page.min' => 'Trang phải lớn hơn hoặc bằng 1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
