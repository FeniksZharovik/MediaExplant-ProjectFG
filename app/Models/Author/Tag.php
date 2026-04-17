<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tag';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama_tag',
        'berita_id',
    ];
}
