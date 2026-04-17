<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StrukturDivisi extends Model
{
    protected $table = 'organisasi_divisi';

    protected $fillable = [
        'id',
        'nama_divisi',
        'row'
    ];

    // Enable string-based UUID support
    public $incrementing = false;
    protected $keyType = 'string';

    public function anggotas()
    {
        return $this->hasMany(StrukturAnggota::class, 'id_divisi');
    }
}