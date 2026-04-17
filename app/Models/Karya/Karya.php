<?php

namespace App\Models\Karya;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Karya extends Model
{
    protected $table = 'karya';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'deskripsi',
        'konten',
        'visibilitas',
        'kategori',
        'media',
        'release_date'
    ];
}
