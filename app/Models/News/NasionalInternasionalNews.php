<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserReact\Reaksi;
use App\Models\Author\Tag;

class NasionalInternasionalNews extends Berita
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

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
            $article->id = (string) Str::uuid();
        });
    }

    public function getFirstImageAttribute(): string
    {
        if (preg_match('/<img[^>]+src="([^">]+)"/i', $this->konten_berita, $matches)) {
            return $matches[1];
        }
        return 'https://via.placeholder.com/400x200';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public function reaksiSuka()
    {
        return $this->hasMany(Reaksi::class, 'item_id', 'id')
            ->where('reaksi_type', 'Berita')
            ->where('jenis_reaksi', 'Suka');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
    }
}
