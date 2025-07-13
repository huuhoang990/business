<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CustomersImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'customer_name' => $row['ten_khach_hang'],
            'email' => $row['email'],
            'tel_num' => $row['so_dien_thoai'],
            'address' => $row['dia_chi'],
        ]);
    }

    public function rules(): array
    {

        return [
            'ten_khach_hang' => 'required|string|min:5|regex:/^[\p{L}\s-]+$/u',
            'email' => 'required|string|email|unique:customers,email',
            'so_dien_thoai' => 'required',
            'dia_chi' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ten_khach_hang.required' => 'Tên khách hàng là bắt buộc.',
            'ten_khach_hang.string' => 'Tên khách hàng phải là chuỗi.',
            'ten_khach_hang.min' => 'Tên khách hàng phải có ít nhất 5 ký tự.',
            'ten_khach_hang.regex' => 'Tên khách hàng chỉ được chứa chữ cái, khoảng trắng và dấu gạch ngang.',
            'email.required' => 'Email là bắt buộc.',
            'email.string' => 'Email phải là chuỗi.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.string' => 'Số điện thoại phải là chuỗi.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.',
            'dia_chi.string' => 'Địa chỉ phải là chuỗi.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự.'
        ];
    }
}
