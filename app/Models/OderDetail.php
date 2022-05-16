<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OderDetail extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'oder_detail';
    public $timestamps = false;
    protected $fillable = [
        'oder_id',
        'color_id',
        'total',
        'quantity',
        'created_at',
        'updated_at',
    ];
    public function getManageColor(){
       return($this->hasOne(ManageColor::class, 'id', 'color_id'));
    }
}
