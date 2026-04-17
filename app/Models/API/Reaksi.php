<?php

namespace App\Models\API;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reaksi extends Model
{
    use HasFactory;

    protected $table = 'reaksi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string'; 
    protected $fillable = [
        'user_id', 'item_id', 'reaksi_type', 'jenis_reaksi', 'tanggal_reaksi'
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::random(12);
            $model->tanggal_reaksi = Carbon::now('Asia/Jakarta');
        });
    }
    
    public function reaksiable()
    {
        return $this->morphTo(__FUNCTION__, 'reaksi_type', 'item_id');
    }
    

    
}

