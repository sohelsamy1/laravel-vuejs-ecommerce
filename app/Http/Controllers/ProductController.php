<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function ListProductByCategory(Request $request): JsonResponse
    {
        $data = Product::where('category_id', $request->id)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }


    public function ListProductByBrand(Request $request): JsonResponse
    {
        $data = Product::where('brand_id', $request->id)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductByRemark(Request $request): JsonResponse
    {
        $data = Product::where('remark', $request->remark)->with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }
}
