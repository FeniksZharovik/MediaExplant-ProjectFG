<?php

namespace App\Models\UserReact;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $table = 'pesan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'pesan',
        'detail_pesan',
        'status_read',
        'status',
        'pesan_type',
        'item_id',
        'created_at'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($report) {
            $report->id = Str::random(12);
            $report->created_at = now();
        });
    }
}
