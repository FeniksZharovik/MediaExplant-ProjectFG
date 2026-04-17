<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;

class Liked extends Model
{
    protected $table = 'reaksi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'item_id', 'jenis_reaksi', 'tanggal_reaksi', 'reaksi_type'];

    public function berita()
    {
        return $this->belongsTo(\App\Models\Author\Published::class, 'item_id');
    }

    public function karya()
    {
        return $this->belongsTo(\App\Models\Karya\Karya::class, 'item_id');
    }

    public function reaksiable()
    {
        return $this->morphTo(null, 'reaksi_type', 'item_id');
    }
}
