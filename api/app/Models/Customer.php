<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_name',
        'email',
        'tel_num',
        'address',
        'is_active'
    ];

    public static function getListCustomersByCondition(array $conditionSearch)
    {
        $page = isset($conditionSearch['page']) ? (int)$conditionSearch['page'] : 1;
        $query = self::select(DB::raw('row_number() OVER () AS row_num'), 'customer_id', 'customer_name', 'address', 'tel_num', 'email', 'is_active');

        if (isset($conditionSearch['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $conditionSearch['customer_name'] . '%');
        }

        if (isset($conditionSearch['email'])) {
            $query->where('email', 'like', '%' . $conditionSearch['email'] . '%');
        }

        if (isset($conditionSearch['address'])) {
            $query->where('address', 'like', '%' . $conditionSearch['address'] . '%');
        }

        if (isset($conditionSearch['is_active'])) {
            $query->where('is_active', $conditionSearch['is_active']);
        }

        $query->orderBy('created_at', 'desc');

        $usersPaginator = $query->paginate(Constants::ROW_PER_PAGE, ['*'], 'page', $page);
        return [
            'list_customers' => $usersPaginator->items(),
            'pagination' => [
                'current_page' => $usersPaginator->currentPage(),
                'total_page' => $usersPaginator->lastPage(),
                'row_per_page' => $usersPaginator->perPage(),
                'total_records' => $usersPaginator->total(),
            ]
        ];
    }

    public static function createCustomer(array $data): self
    {
        return self::create([
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'tel_num' => $data['tel_num'],
            'address' => $data['address'],
            'is_active' => $data['is_active'],
        ]);
    }

    public static function getExportListCustomers(array $conditionSearch)
    {
        $customerList = null;
        $query = self::select('customer_name', 'address', 'tel_num', 'email');
        $haveCondition = false;
        if (isset($conditionSearch['customer_name'])) {
            $haveCondition = true;
            $query->where('customer_name', 'like', '%' . $conditionSearch['customer_name'] . '%');
        }

        if (isset($conditionSearch['email'])) {
            $haveCondition = true;
            $query->where('email', 'like', '%' . $conditionSearch['email'] . '%');
        }

        if (isset($conditionSearch['address'])) {
            $haveCondition = true;
            $query->where('address', 'like', '%' . $conditionSearch['address'] . '%');
        }

        if (isset($conditionSearch['is_active'])) {
            $haveCondition = true;
            $query->where('is_active', $conditionSearch['is_active']);
        }

        $query->orderBy('created_at', 'desc');

        if($haveCondition) {
            $customerList = $query->get()->toArray();
        } else {
            $customerList = $query->paginate(Constants::ROW_PER_PAGE, ['*'], 'page', 1)->items();
        }

        return $customerList;
    }
}
