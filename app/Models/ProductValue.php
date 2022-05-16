<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductValue extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'product_values';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'type_id',
        'value_id',
        'created_at',
        'updated_at',
    ];
    public function getValue(){
       return ($this->hasOne(Values::class, 'id', 'value_id'));
    }
    public function getType(){
         return ($this->hasOne(Atribute::class, 'id', 'type_id'));
    }
    public function getProduct(){
        return ($this->hasOne(Product::class, 'id', 'product_id'));
    }

}
