<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Values extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'values';
    public $timestamps = false;
    protected $fillable = [
        'value',
        'type_id',
        'created_at',
        'updated_at',
    ];
    public function getAtribute(){
        return $this->belongsTo(Atribute::class, 'type_id', 'id');
    }
}
