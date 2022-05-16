<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OderState extends Model
{
    use HasFactory;

    protected $table = 'status_active';
    public $timestamps = false;
    protected $fillable = [
        'oder_id',
        'user_id',
        'active',
        'content',
        'created_at',
        'updated_at',
    ];
    public function getUser(){
        return ($this->hasOne(User::class, 'id', 'user_id'));
    }
}
