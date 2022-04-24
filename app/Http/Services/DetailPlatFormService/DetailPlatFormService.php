<?php

namespace App\Http\Services\DetailPlatFormService;

use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\BusinessPlatform;

class DetailPlatFormService
{
    public function firstWherePlatForm($slug)
    {
        return (BusinessPlatform::where('slug', $slug)->firstOrFail());
    }
    public function wherePlatForm($slug)
    {
        return (BusinessPlatform::where('slug', $slug)->firstOrFail());
    }
    public function whereInProduct($array)
    {
        return (Product::whereIn('category_id', $array)->get());
    }
    public function getProductWhereIn($array)
    {
        return (Product::whereIn('id', $array));
    }
}
