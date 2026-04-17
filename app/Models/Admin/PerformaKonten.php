<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformaKategoriHarian extends Model
{
    protected $table = 'performa_kategori_harian';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'datetime',
        'tipe_konten',
        'kategori',
        'view_count',
        'like_count',
        'dislike_count'
    ];

    // Jika ingin semua kolom bisa diisi sekaligus
    // protected $guarded = [];

    /**
     * Cast tipe data agar lebih aman
     */
    protected $casts = [
        'datetime' => 'date',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'dislike_count' => 'integer'
    ];
}