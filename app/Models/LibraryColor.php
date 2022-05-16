<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryColor extends Model
{
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'library_color';
    public $timestamps = false;
    protected $fillable = [
        'manage_color_id',
        'thumb',
        'created_at',
        'updated_at',
    ];
    public function getColor(){
        return $this->belongsTo(ManageColor::class, 'manage_color_id', 'id');
    }

}

