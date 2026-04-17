<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;

class Bookmarked extends Model
{
    protected $table = 'bookmark';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'item_id', 'tanggal_bookmark'];

    public function berita()
    {
        return $this->belongsTo(\App\Models\Author\Published::class, 'item_id', 'id');
    }

    public function karya()
    {
        return $this->belongsTo(\App\Models\Karya\Karya::class, 'item_id');
    }
}
