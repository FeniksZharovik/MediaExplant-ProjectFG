<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tag';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama_tag', 'berita_id'
    ];

        public function berita()
    {
        return $this->belongsTo(Berita::class, 'berita_id', 'id');
    }

    
}
