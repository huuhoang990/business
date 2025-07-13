<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;

class CreateCustomerRequest extends FormRequest
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
            'customer_name' => 'required|string|min:5',
            'email' => 'required|string|email|unique:customers,email',
            'address' => 'required|string|max:255',
            'tel_num' => 'required|numeric',
            'is_active' => 'boolean|nullable',
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Vui lòng nhập tên khách hàng',
            'customer_name.min' => 'Tên khách hàng phải lớn hơn 5 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký.',
            'address.required' => 'Địa chỉ không được bỏ trống',
            'address.min' => 'Địa chỉ phải lớn hơn 5 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'tel_num.required' => 'Số điện thoại không được bỏ trống',
            'tel_num.numeric' => 'Số điện thoại phải là một số hợp lệ',
            'is_active.boolean' => 'Trạng thái hoạt động phải là true hoặc false',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
