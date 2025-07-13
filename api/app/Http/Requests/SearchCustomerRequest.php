<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;

class SearchCustomerRequest extends FormRequest
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
            'customer_name' => 'string|nullable|max:255',
            'email' => 'string|nullable|max:255',
            'tel_num' => 'numeric|nullable|digits_between:10,15',
            'address' => 'string|nullable|max:255',
            'is_active' => 'numeric|nullable|in:0,1',
            'page' => 'numeric|nullable|min:1',
        ];
    }

    public function messages()
    {
        return [
            'customer_name.string' => 'Tên khách hàng phải là một chuỗi',
            'customer_name.max' => 'Tên khách hàng không được vượt quá 255 ký tự',
            'email.string' => 'Email phải là một chuỗi',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'tel_num.numeric' => 'Số điện thoại phải là một số',
            'tel_num.digits_between' => 'Số điện thoại phải có từ 10 đến 15 chữ số',
            'address.string' => 'Địa chỉ phải là một chuỗi',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'is_active.numeric' => 'Trạng thái hoạt động phải là một số',
            'is_active.in' => 'Trạng thái hoạt động phải là 0 hoặc 1',
            'page.numeric' => 'Trang phải là một số',
            'page.min' => 'Trang phải lớn hơn hoặc bằng 1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
