<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'category';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'content',
        'slug',
        'active',
        'business_platform_id',
        'created_at',
        'updated_at',
    ];
    public function getBusiness(){
        return $this->belongsTo(BusinessPlatform::class, 'business_platform_id', 'id');
    }
    public function getProduct(){
        return($this->hasMany(Product::class, 'category_id', 'id'));
    }
    public function getCategoryChild(){
        return ($this->hasMany(Category::class, 'parent_id', 'id'));
    }

}
