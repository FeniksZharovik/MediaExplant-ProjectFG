<?php

namespace App\Models\Produk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Buletin extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'judul',
        'kategori',
        'media',
        'deskripsi',
        'release_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public static function getHomeBuletin($limit = 6)
    {
        $satuMingguLalu = now('Asia/Jakarta')->subDays(7)->toDateTimeString();

        $query = self::select('produk.*')
            ->where('kategori', 'Buletin')
            ->where('visibilitas', 'public')
            ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as like_count
            FROM reaksi
            WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Produk'
            GROUP BY item_id) as r"), 'produk.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as komentar_count
            FROM komentar
            WHERE komentar_type = 'Produk'
            GROUP BY item_id) as k"), 'produk.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as bookmark_count
            FROM bookmark
            WHERE bookmark_type = 'Produk'
            GROUP BY item_id) as b"), 'produk.id', '=', 'b.item_id')
            ->where('release_date', '>=', $satuMingguLalu)
            ->orderByDesc(DB::raw('
            (view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2)
        '))
            ->orderByDesc('release_date')
            ->take($limit);

        $results = $query->get();

        // Jika hasil kurang dari $limit, ambil tambahan dari semua data (fallback)
        if ($results->count() < $limit) {
            $tambahan = self::select('produk.*')
                ->where('kategori', 'Buletin')
                ->where('visibilitas', 'public')
                ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as like_count
                FROM reaksi
                WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Produk'
                GROUP BY item_id) as r"), 'produk.id', '=', 'r.item_id')
                ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as komentar_count
                FROM komentar
                WHERE komentar_type = 'Produk'
                GROUP BY item_id) as k"), 'produk.id', '=', 'k.item_id')
                ->leftJoin(DB::raw("(SELECT item_id, COUNT(*) as bookmark_count
                FROM bookmark
                WHERE bookmark_type = 'Produk'
                GROUP BY item_id) as b"), 'produk.id', '=', 'b.item_id')
                ->orderByDesc(DB::raw('
                (view_count * 1) +
                (COALESCE(r.like_count, 0) * 2) +
                (COALESCE(k.komentar_count, 0) * 3) +
                (COALESCE(b.bookmark_count, 0) * 2)
            '))
                ->orderByDesc('release_date')
                ->take($limit - $results->count())
                ->get();

            $results = $results->merge($tambahan);
        }

        return $results;
    }
}
