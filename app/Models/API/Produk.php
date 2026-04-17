<?php

namespace App\Models\API;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'deskripsi',
        'kategori',
        'media',
        'cover',
        'release_date',
        'visibilitas',
        'view_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable', 'bookmark_type', 'item_id');
    }

    public function reaksis()
    {
        return $this->morphMany(Reaksi::class, 'reaksiable', 'reaksi_type', 'item_id');
    }

    public function komentars()
    {
        return $this->morphMany(Komentar::class, 'komentarable', 'komentar_type', 'item_id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
    }

    public function getMediaBase64Attribute()
    {
        if (!$this->media) {
            return null;
        }

        $mime = finfo_buffer(finfo_open(), $this->media, FILEINFO_MIME_TYPE);
        return "data:$mime;base64," . base64_encode($this->media);
    }
}
