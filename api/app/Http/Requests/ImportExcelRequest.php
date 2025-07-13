<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseHelper;

class ImportExcelRequest extends FormRequest
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
            'import_customer_file' => 'required|file|mimes:xlsx,csv,xls|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'import_customer_file.required' => 'Vui lòng chọn tệp để nhập khẩu.',
            'import_customer_file.file' => 'Tệp nhập khẩu phải là một tệp.',
            'import_customer_file.mimes' => 'Tệp nhập khẩu chỉ hỗ trợ định dạng: xlsx, xls.',
            'import_customer_file.max' => 'Kích thước tệp nhập khẩu không được vượt quá 2MB.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->responseValidationError($validator);
    }
}
