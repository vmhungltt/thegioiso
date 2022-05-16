<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atribute extends Model
{
    use HasFactory;
    protected $table = 'types';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];
    public function getValue(){
        return ($this->hasMany(Values::class, 'type_id', 'id'));
    }
}
