<?php

namespace App\Exports;

use App\Models\customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    protected $customers;

    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return collect($this->customers)->map(function($item) {
            return [
                'Tên khách hàng' => $item['customer_name'],
                'Email' => $item['email'],
                'Số điện thoại' => $item['tel_num'],
                'Địa chỉ' => $item['address'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'Email',
            'Số điện thoại',
            'Địa chỉ',
        ];
    }
}
