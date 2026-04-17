<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Berita extends Model
{
    use HasFactory;
    protected $table = 'berita';
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena ID berupa UUID
    protected $keyType = 'string';
    public $timestamps = false; // Nonaktifkan timestamps

    protected $fillable = [
        'id',
        'judul',
        'tanggal_diterbitkan',
        'user_id',
        'kategori',
        'konten_berita',
        'visibilitas',
    ];
    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}