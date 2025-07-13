<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\SearchProductRequest;
use App\Models\Product;
use App\Http\Requests\EditProductRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\GetDetailProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;

    public function getListProductsByCondition(SearchProductRequest $request)
    {
        $conditionSearch = $request->validated();
        $products = Product::getListProductsByCondition($conditionSearch);
        return $this->successResponse(
            "Lấy danh sách sản phẩm thành công.",
            $products
        );
    }

    public function addProduct(AddProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('upload_image')) {
            $image = $request->file('upload_image');
            $imagePath = $image->store('products', 'public');
            $data['product_image'] = $imagePath;
        }
        $product = Product::createProduct($data);
        if (!$product) {
            return $this->errorResponse("Thêm sản phẩm không thành công.", 400);
        }
        return $this->successResponse("Thêm sản phẩm thành công.");
    }

    public function getDetailProduct(GetDetailProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::getDetailProduct($data['product_id']);
        if (!$product) {
            return $this->errorResponse("Sản phẩm không tồn tại.", 404);
        }

        $responseData = [
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'product_price' => $product['product_price'],
            'is_sales' => $product['is_sales'],
            'description' => $product['description'],
            'data_image' => null,
        ];

        $filePath = $product['product_image'];
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $fileContents = Storage::disk('public')->get($filePath);
            $base64Image = base64_encode($fileContents);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeType = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
            };
            $responseData['data_image'] = "data:{$mimeType};base64,{$base64Image}";
        }

        return $this->successResponse(
            "Lấy chi tiết sản phẩm thành công.",
            $responseData);
    }

    public function editProduct(EditProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::getDetailProduct($data);
        $oldImage = $product->product_image;
        $isUploadNewImage = $request->hasFile('upload_image');

        if ($isUploadNewImage) {
            $image = $request->file('upload_image');
            $imagePath = $image->store('products', 'public');
            $data['product_image'] = $imagePath;
        }

        $result = Product::editProduct($data);
        if (!$result) {
            return $this->errorResponse("Cập nhật sản phẩm không thành công.", 400);
        }

        if ($isUploadNewImage || $data['delete_old_image']) {
            Storage::disk('public')->delete($oldImage);
        }

        return $this->successResponse("Cập nhật sản phẩm thành công.");
    }
}
