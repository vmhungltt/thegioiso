<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'news';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'product_id',
        'category_news_id',
        'description',
        'content',
        'slug',
        'thumb',
        'created_at',
        'updated_at',
    ];
    public function getProduct(){
       return ($this->hasOne(Product::class, 'id', 'product_id'));
    }
    public function getCategoryNews(){
        return ($this->hasOne(CategoryNews::class, 'id', 'category_news_id'));
    }
}
