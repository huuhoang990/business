<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;

class AddProductRequest extends FormRequest
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
            'product_name' => 'required|string|min:5|max:255',
            'product_price' => 'required|numeric|min:0',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=1024,max_height=1024',
            'description' => 'nullable|string',
            'is_sales' => 'boolean|nullable',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm',
            'product_name.string' => 'Tên sản phẩm phải là một chuỗi',
            'product_name.min' => 'Tên sản phẩm phải lớn hơn 5 ký tự',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'product_price.required' => 'Giá bán không được để trống.',
            'product_price.numeric' => 'Giá bán chỉ được nhập số.',
            'product_price.min' => 'Giá bán không được nhỏ hơn 0',
            'upload_image.image' => 'Hình ảnh sản phẩm phải là một tệp hình ảnh hợp lệ',
            'upload_image.mimes' => 'Hình ảnh sản phẩm chỉ hỗ trợ các định dạng: jpeg, png, jpg, gif, svg',
            'upload_image.max' => 'Kích thước hình ảnh sản phẩm không được vượt quá 2MB',
            'upload_image.dimensions' => 'Kích thước không quá 1024px',
            'is_sales.boolean' => 'Trạng thái bán hàng phải là true hoặc false',
            'is_sales.nullable' => 'Trạng thái không được để trống.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
