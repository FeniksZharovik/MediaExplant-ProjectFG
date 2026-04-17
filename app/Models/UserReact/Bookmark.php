<?php

namespace App\Models\UserReact;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmark';

    protected $fillable = [
        'id', 'user_id', 'tanggal_bookmark', 'bookmark_type', 'item_id',
    ];

    public $timestamps = false;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
