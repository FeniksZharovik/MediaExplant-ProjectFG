<?php

namespace App\Models\API;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karya extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'karya';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id','user_id', 'kreator', 'judul','deskripsi', 'konten', 'visibilitas','kaegori','media','release_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'uid');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
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


