<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Author\Tag;
use App\Models\UserReact\Reaksi;

class HomeNews extends Berita
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'judul',
        'konten_berita',
        'tanggal_diterbitkan',
        'kategori',
        'visibilitas',
    ];

    public static function getBeritaTeratasHariIni()
    {
        $now = Carbon::now('Asia/Jakarta');
        $sevenDaysAgo = $now->copy()->subDays(7)->toDateString();

        $categories = [
            'Kampus',
            'Nasional',
            'Internasional',
            'Liputan Khusus',
            'Teknologi',
            'Kesenian',
            'Hiburan',
            'Kesehatan',
            'Olahraga',
            'Opini',
            'Esai'
        ];

        $results = collect();

        foreach ($categories as $kategori) {
            $berita = self::where('kategori', $kategori)
                ->where('visibilitas', 'public')
                ->whereDate('tanggal_diterbitkan', '>=', $sevenDaysAgo)
                ->withCount([
                    'reaksi as suka_count' => function ($query) {
                        $query->where('jenis_reaksi', 'Suka');
                    },
                    'komentar as komentar_count' => function ($query) {
                        $query->where('komentar_type', 'Berita');
                    },
                    'bookmark as bookmark_count' => function ($query) {
                        $query->where('bookmark_type', 'Berita');
                    }
                ])
                ->select('*')
                ->selectRaw('
                (view_count * 1) +
                ((SELECT COUNT(*) FROM reaksi WHERE item_id = berita.id AND jenis_reaksi = "Suka" AND reaksi_type = "Berita") * 2) +
                ((SELECT COUNT(*) FROM komentar WHERE item_id = berita.id AND komentar_type = "Berita") * 3) +
                ((SELECT COUNT(*) FROM bookmark WHERE item_id = berita.id AND bookmark_type = "Berita") * 2)
                as skor_total
            ')
                ->having('skor_total', '>', 0)
                ->orderByDesc('skor_total')
                ->orderByDesc('tanggal_diterbitkan')
                ->first();

            if ($berita) {
                $results->push($berita);
            } else {
                // Jika tidak ada dengan skor > 0, ambil berita terbaru saja dari kategori itu
                $fallback = self::where('kategori', $kategori)
                    ->where('visibilitas', 'public')
                    ->orderByDesc('tanggal_diterbitkan')
                    ->first();

                if ($fallback) {
                    $results->push($fallback);
                }
            }
        }

        return $results;
    }

    public function getFirstImageAttribute(): string
    {
        if (preg_match('/<img[^>]+src="([^">]+)"/i', $this->konten_berita, $matches)) {
            return $matches[1];
        }
        return $this->gambar ?? 'https://via.placeholder.com/400x200';
    }

    public function reaksiSuka()
    {
        return $this->hasMany(Reaksi::class, 'item_id')
            ->where('reaksi_type', 'Berita')
            ->where('jenis_reaksi', 'Suka');
    }

    public function getCategorySlugAttribute()
    {
        $mapping = [
            'Kampus' => 'kampus',
            'Kesehatan' => 'kesehatan',
            'KesenianHiburan' => 'kesenian-hiburan',
            'Liputan Khusus' => 'liputan-khusus',
            'NasionalInternasional' => 'nasional-internasional',
            'Olahraga' => 'olahraga',
            'OpiniEsai' => 'opini-esai',
            'Teknologi' => 'teknologi',
            'Hiburan' => 'hiburan',
            'Esai' => 'esai',
        ];

        return $mapping[$this->kategori] ?? Str::slug($this->kategori);
    }

    public function getArticleUrlAttribute()
    {
        return url("/kategori/{$this->category_slug}/read?a={$this->id}");
    }

    public function getExcerptAttribute()
    {
        $cleanedContent = preg_replace('/&nbsp;/i', ' ', strip_tags($this->konten_berita));
        return Str::limit($cleanedContent, 150);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }

    public function reaksi()
    {
        return $this->hasMany(\App\Models\UserReact\Reaksi::class, 'item_id', 'id')
            ->where('reaksi_type', 'berita');
    }

    public function komentar()
    {
        return $this->hasMany(\App\Models\UserReact\Komentar::class, 'item_id', 'id')
            ->where('komentar_type', 'Berita');
    }

    public function bookmark()
    {
        return $this->hasMany(\App\Models\UserReact\Bookmark::class, 'item_id', 'id')
            ->where('bookmark_type', 'Berita');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'berita_id');
    }
}
