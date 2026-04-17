<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StrukturAnggota extends Model
{
    protected $table = 'organisasi_anggota';

    protected $fillable = [        
        'id',
        'title_perangkat',
        'id_divisi',
        'uid',
    ];

    // Enable string-based UUID support
    public $incrementing = false;
    protected $keyType = 'string';

    public function divisi()
    {
        return $this->belongsTo(StrukturDivisi::class, 'id_divisi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}