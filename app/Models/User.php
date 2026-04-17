<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\API\Bookmark;
use App\Models\API\Komentar;
use App\Models\API\Reaksi;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'user';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'nama_pengguna',
        'password',
        'email',
        'role',
        'nama_lengkap',
    ];

    protected $hidden = [
        'password',
    ];

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'uid');
    }

    public function reaksi()
    {
        return $this->hasMany(Reaksi::class, 'user_id', 'uid');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'user_id', 'uid');
    }
}
