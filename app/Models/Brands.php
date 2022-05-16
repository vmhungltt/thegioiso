<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'brands';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'active',
        'created_at',
        'updated_at',
    ];
}
