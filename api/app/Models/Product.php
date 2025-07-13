<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'products';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_image',
        'product_price',
        'is_sales',
        'description'
    ];

    public static function getListProductsByCondition(array $conditionSearch)
    {
        $page = isset($conditionSearch['page']) ? (int)$conditionSearch['page'] : 1;
        $query = self::select(DB::raw('row_number() OVER () AS row_num'), 'product_id', 'product_name', 'product_image', 'product_price', 'is_sales', 'description');

        if (isset($conditionSearch['product_name'])) {
            $query->where('product_name', 'like', '%' . $conditionSearch['product_name'] . '%');
        }

        if (isset($conditionSearch['is_sales'])) {
            $query->where('is_sales', $conditionSearch['is_sales']);
        }


        if (isset($conditionSearch['product_price_min'])) {
            $query->where('product_price', '>=', $conditionSearch['product_price_min']);
        }

        if (isset($conditionSearch['product_price_max'])) {
            $query->where('product_price', '<=', $conditionSearch['product_price_max']);
        }

        $query->orderBy('created_at', 'desc');

        $productsPaginator = $query->paginate(Constants::ROW_PER_PAGE, ['*'], 'page', $page);
        return [
            'list_products' => $productsPaginator->items(),
            'pagination' => [
                'current_page' => $productsPaginator->currentPage(),
                'total_page' => $productsPaginator->lastPage(),
                'row_per_page' => $productsPaginator->perPage(),
                'total_records' => $productsPaginator->total(),
            ]
        ];
    }

    public static function createProduct(array $data): self
    {
        $count = Product::count();
        $productData['product_id'] = strtoupper(substr($data['product_name'], 0, 1)) . str_pad($count + 1, 9, '0', STR_PAD_LEFT);
        $productData['product_name'] = $data['product_name'];
        $productData['product_image'] = $data['product_image'] ?? null;
        $productData['product_price'] = $data['product_price'];
        $productData['is_sales'] = $data['is_sales'];
        $productData['description'] = $data['description'] ?? null;

        return self::create($productData);
    }

    public static function getDetailProduct($productId): self
    {
        return self::select(
            'product_id',
            'product_name',
            'product_image',
            'product_price',
            'is_sales',
            'description'
        )->where('product_id', $productId)
            ->firstOrFail();
    }

    public static function editProduct(array $data): bool
    {
        $product = self::where('product_id', $data['product_id'])->firstOrFail();
        $product->product_name = $data['product_name'];
        $product->product_price = $data['product_price'];
        $product->is_sales = $data['is_sales'];
        $product->description = $data['description'] ?? null;

        if (isset($data['product_image'])) {
            $product->product_image = $data['product_image'];
        }

        return $product->save();
    }
}
