<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TentangKami extends Model
{
    protected $table = 'tentang_kami';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'id', 'email', 'nomorHp', 'tentangKami', 'fokus_utama', 'facebook', 'instagram', 'linkedin', 'youtube', 'kodeEtik', 'explantContributor'
    ];
}