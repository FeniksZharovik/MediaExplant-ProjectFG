<?php

namespace App\Models\UserReact;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'isi_komentar',
        'tanggal_komentar',
        'komentar_type',
        'item_id',
        'parent_id',
    ];

    protected $dates = [
        'tanggal_komentar',
    ];

    // Relasi ke user (user_id reference ke uid di table users)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'uid');
    }

    // Relasi ke komentar induk (jika ini adalah balasan komentar)
    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id');
    }

    // Relasi ke balasan-balasannya
    public function replies()
    {
        return $this->hasMany(Komentar::class, 'parent_id');
    }

    public function deleteWithReplies()
    {
        foreach ($this->replies as $reply) {
            $reply->deleteWithReplies(); 
        }
        $this->delete();
    }

    // Format tanggal jika ingin ditampilkan rapi
    public function getTanggalKomentarAttribute($value)
    {
        return Carbon::parse($value);
    }
}
