<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageColor extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'manage_color';
    public $timestamps = false;
    protected $fillable = [
        'name_color',
        'product_id',
        'price',
        'price_sale',
        'quantity',
        'thumb',
        'active',
        'created_at',
        'updated_at',
    ];
    public function getProduct(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function getLibraryColor(){
        return ($this->hasMany(LibraryColor::class, 'manage_color_id', 'id'));
    }
}
