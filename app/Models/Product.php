<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'product';
    public $timestamps = false;
    protected $fillable = [
        'view_model',
        'name',
        'category_id',
        'description',
        'content',
        'slug',
        'active',
        'thumb',
        'price',
        'price_sale',
        'admin_post_id',
        'created_at',
        'updated_at',
    ];
    public function getCategory(){
        return $this->belongsTo(Category::class, 'category_id', 'id'); // lấy ra  1 danh mục thông qua sản phẩm
          // category_id là cột khóa ngoại (unique liên kết với id của bảng category) của bảng product
             // id là khóa chính của bảng category
    }
    public function getColor(){
        return ($this->hasMany(ManageColor::class, 'product_id', 'id'));

    }
    public function getComment(){
        return ($this->hasMany(Comment::class, 'product_id', 'id'));
    }
   public function getProductValue(){
    return ($this->hasMany(ProductValue::class, 'product_id', 'id'));
   }
}
