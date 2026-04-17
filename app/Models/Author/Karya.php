<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $table = 'karya';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'creator',
        'judul',
        'deskripsi',
        'kategori',
        'user_id',
        'media',
        'release_date',
        'visibilitas',
        'konten'
    ];
}
