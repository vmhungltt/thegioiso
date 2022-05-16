<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'comment';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'parent_id',
        'thumb',
        'active',
        'vote',
        'content',
        'name',
        'number_phone',
        'email',
        'created_at',
        'updated_at',
    ];
    public function getCommentChild(){
        return ($this->hasMany(Comment::class, 'parent_id', 'id'));
    }
    public function getProduct(){
        return ($this->hasOne(Product::class, 'id', 'product_id'));
    }
}
