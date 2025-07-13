<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\SearchCustomerRequest;
use App\Models\Customer;
use App\Http\Requests\CreateCustomerRequest;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\CustomersImport;

class CustomerController extends Controller
{
    use ApiResponse;

    public function getListCustomersByCondition(SearchCustomerRequest $request)
    {
        $conditionSearch = $request->validated();
        $customers = Customer::getListCustomersByCondition($conditionSearch);
        return $this->successResponse(
            "Lấy danh sách khách hàng thành công.",
            $customers
        );
    }

    public function createCustomer(CreateCustomerRequest $createCustomerRequest)
    {
        $data = $createCustomerRequest->validated();
        $customer = Customer::createCustomer($data);
        if (!$customer) {
            return $this->errorResponse("Thêm khách hàng không thành công.", Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse("Thêm khách hàng thành công.");
    }

    public function exportCustomerExcel(SearchCustomerRequest $request)
    {
        $conditionSearch = $request->validated();
        $customers = Customer::getExportListCustomers($conditionSearch);
        return Excel::download(new CustomerExport($customers), 'Customer.xlsx');
    }

    public function importCustomerExcel(ImportExcelRequest $request)
    {
        $file = $request->file('import_customer_file');

        try {
            Excel::import(new CustomersImport(), $file);
            return $this->successResponse("Import khách hàng thành công.");
        } catch (\Exception $e) {
            return $this->errorResponse("Import khách hàng thất bại: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
