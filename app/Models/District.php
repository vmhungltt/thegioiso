<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'devvn_quanhuyen';
    public $timestamps = false;
    public function getWards(){
        return ($this->hasMany(Wards::class, 'maqh', 'maqh'));
    }
}

