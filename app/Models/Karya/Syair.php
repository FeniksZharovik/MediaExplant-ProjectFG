<?php

namespace App\Models\Karya;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserReact\Reaksi;
use Carbon\Carbon;

class Syair extends Model
{
    protected $table = 'karya';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'deskripsi',
        'konten',
        'visibilitas',
        'kategori',
        'media',
        'release_date'
    ];

    public static function getTeratasMingguan($limit = 6)
    {
        $mingguLalu = Carbon::now('Asia/Jakarta')->subDays(7);

        $dataBaru = self::where('kategori', 'syair')
            ->where('visibilitas', 'public')
            ->where('release_date', '>=', $mingguLalu)
            ->selectRaw('
            karya.*,
            (view_count * 1 +
                (select count(*) from reaksi where reaksi.item_id = karya.id and reaksi_type = "Karya" and jenis_reaksi = "Suka") * 2 +
                (select count(*) from komentar where komentar.item_id = karya.id and komentar_type = "Karya") * 3 +
                (select count(*) from bookmark where bookmark.item_id = karya.id and bookmark_type = "Karya") * 2
            ) as skor
        ')
            ->get();

        $dataLama = self::where('kategori', 'syair')
            ->where('visibilitas', 'public')
            ->where('release_date', '<', $mingguLalu)
            ->selectRaw('
            karya.*,
            (view_count * 1 +
                (select count(*) from reaksi where reaksi.item_id = karya.id and reaksi_type = "Karya" and jenis_reaksi = "Suka") * 2 +
                (select count(*) from komentar where komentar.item_id = karya.id and komentar_type = "Karya") * 3 +
                (select count(*) from bookmark where bookmark.item_id = karya.id and bookmark_type = "Karya") * 2
            ) as skor
        ')
            ->get();

        $semua = $dataBaru->merge($dataLama)->sortByDesc(function ($item) {
            return [$item->skor, $item->release_date];
        })->values();

        return $semua->take($limit);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public function reaksiSuka()
    {
        return $this->hasMany(Reaksi::class, 'item_id', 'id')
            ->where('reaksi_type', 'Karya')
            ->where('jenis_reaksi', 'Suka');
    }

    public function komentar()
    {
        return $this->hasMany(\App\Models\UserReact\Komentar::class, 'item_id', 'id')
            ->where('komentar_type', 'Karya');
    }

    public function bookmark()
    {
        return $this->hasMany(\App\Models\UserReact\Bookmark::class, 'item_id', 'id')
            ->where('bookmark_type', 'Karya');
    }
}
