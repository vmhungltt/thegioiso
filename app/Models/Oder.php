<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oder extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'oder';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'transport_fee',
        'discount_code',
        'note',
        'name',
        'phone_number',
        'address_detail',
        'total',
        'active',
        'created_at',
        'updated_at',
    ];
   public function getOderState(){
       return ($this->hasMany(OderState::class, 'oder_id', 'id'));
   }
   public function getOderDetail(){
       return ($this->hasMany(OderDetail::class, 'oder_id', 'id'));
   }
}
