<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;


class BrandController extends Controller
{
    public function BrandList(): JsonResponse
    {
        $data = Brand::select('id', 'brand_name','brand_img')->get();
        return ResponseHelper::Out('success', $data, 200);
    }
}
