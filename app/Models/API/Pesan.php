<?php

namespace App\Models\API;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'pesan',
        'created_at',
        'status_read',
        'status',
        'detail_pesan',
        'pesan_type',
        'item_id',
        'nama',        
        'email',
        'media',
        'star',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'uid');
    }

    public function pesanable()
    {
        return $this->morphTo(__FUNCTION__, 'pesan_type', 'item_id');
    }

   
}
