<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'product_types';
    public $timestamps = false;
    protected $fillable = [
        'platform_id',
        'type_id',
        'created_at',
        'updated_at',
    ];
    public function getType(){
        return ($this->hasOne(Atribute::class, 'id', 'type_id'));
    }
}
