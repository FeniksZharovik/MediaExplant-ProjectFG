<?php

namespace App\Models\API;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmark';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    protected $fillable = ['user_id', 'tanggal_bookmark', 'bookmark_type', 'item_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::random(12);
            $model->tanggal_bookmark = Carbon::now('Asia/Jakarta');
        });
    }

    public function bookmarkable()
    {
        return $this->morphTo(__FUNCTION__, 'bookmark_type', 'item_id');
    }
}
