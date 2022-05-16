<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccrountCart extends Model
{
    use HasFactory;
    protected $table = 'accrount_cart';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'color_id',
        'quantity',
        'created_at',
        'updated_at',
    ];
    public function getColor(){
        return ($this->hasOne(ManageColor::class, 'id', 'color_id'));
    }
}
