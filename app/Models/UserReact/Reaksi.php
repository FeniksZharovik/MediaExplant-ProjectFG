<?php

namespace App\Models\UserReact;

use Illuminate\Database\Eloquent\Model;

class Reaksi extends Model
{
    protected $table = 'reaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'jenis_reaksi',
        'tanggal_reaksi',
        'reaksi_type',
        'item_id'
    ];

    public $timestamps = false;
}
