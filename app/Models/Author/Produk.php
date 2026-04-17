<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; 

    protected $fillable = [
        'id', 'judul', 'deskripsi', 'kategori', 'media', 'cover', 'release_date', 'user_id', 'visibilitas'
    ];
}
