<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlatform extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'business-platform';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'slug',
        'active',
        'created_at',
        'updated_at',
    ];
    public function getProductType(){
        return ($this->hasMany(ProductType::class, 'platform_id', 'id'));
    }
    public function getProduct()
    {
        return $this->hasManyThrough(
            Product::class, // bảng sản phẩm
            Category::class, // bảng danh mục
            'business_platform_id', // id_nentang của bảng danh mục
            'category_id', // id_danhmuc của bảng sản phẩm
            'id', // id khóa chính của bảng nền tảng
            'id' // id khóa chính của bảng danh mục
        );
    }
    public function getCategory(){
        return ($this->hasMany(Category::class, 'business_platform_id', 'id'));
    }

}
