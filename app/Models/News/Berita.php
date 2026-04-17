<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\UserReact\Reaksi;
use App\Models\User;
use App\Models\Author\Tag;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'konten_berita',
        'tanggal_diterbitkan',
        'kategori',
        'visibilitas',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            // Generate UUID yang akan dikonversi menjadi 12 karakter acak
            $article->id = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 12)), 0, 12);
        });
    }

    public function reaksi()
    {
        return $this->morphMany(\App\Models\UserReact\Reaksi::class, 'reaksiable', 'reaksi_type', 'item_id');
    }

    /**
     * Relasi ke penulis berita
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
    }
}
