<?php

namespace App\Models\API;



use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'isi_komentar', 'tanggal_komentar', 'komentar_type', 'item_id', 'parent_id'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::random(12);
            $model->tanggal_komentar = Carbon::now('Asia/Jakarta');
        });
    }

    public function komentarable()
    {
        return $this->morphTo(__FUNCTION__, 'komentar_type', 'item_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }


    // Balasan komentar
    public function replies()
    {
        return $this->hasMany(Komentar::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id');
    }
}
