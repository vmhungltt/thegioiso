<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryNews extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'category_news';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'slug',
        'created_at',
        'updated_at',
    ];
    public function getNews(){
        return ($this->hasMany(News::class, 'category_news_id', 'id'));
    }
}
