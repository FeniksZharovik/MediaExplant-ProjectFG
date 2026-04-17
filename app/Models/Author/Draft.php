<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Draft extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'judul', 'tanggal_diterbitkan', 'view_count', 'user_id',
        'kategori', 'konten_berita', 'visibilitas'
    ];

    protected $appends = ['published_ago'];

    public function getPublishedAgoAttribute()
    {
        return Carbon::parse($this->tanggal_diterbitkan)->diffForHumans();
    }
}
