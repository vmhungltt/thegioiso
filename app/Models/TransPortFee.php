<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransPortFee extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'transport_fee';
    public $timestamps = false;
    protected $fillable = [
        'city',
        'district',
        'ward',
        'ship',
        'created_at',
        'updated_at',
    ];
}
