<?php

namespace App\Models\API;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Berita extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'judul', 'konten_berita', 'tanggal_diterbitkan', 'kategori','view_count', 'user_id', 'visibilitas'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'uid');
    }

    public function tags()  
    {
        return $this->hasMany(Tag::class, 'berita_id', 'id');
    }


    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable','bookmark_type','item_id');
    }

    public function reaksis()
    {
        return $this->morphMany(Reaksi::class, 'reaksiable', 'reaksi_type', 'item_id');
    }
    
    public function komentars()
    {
        return $this->morphMany(Komentar::class, 'komentarable', 'komentar_type', 'item_id');
    }
}


