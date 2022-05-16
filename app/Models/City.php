<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'devvn_tinhthanhpho';
    public $timestamps = false;
    public function getDistrict(){
        return ($this->hasMany(District::class, 'matp', 'matp'));
    }
}
