<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destinasi extends Model
{
    protected $table = 'destinasi';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'c1_jarak',
        'c2_biaya',
        'c3_fasilitas',
        'c4_keindahan',
        'c5_kebersihan',
        'c6_aktivitas',
        'total_fasilitas',
        'total_aktivitas',
        'fasilitas_list',
        'aktivitas_list',
        'foto',
        'foto_list',
    ];

    protected $casts = [
        'c1_jarak' => 'float',
        'c2_biaya' => 'float',
        'c3_fasilitas' => 'integer',
        'c4_keindahan' => 'integer',
        'c5_kebersihan' => 'integer',
        'c6_aktivitas' => 'integer',
        'total_fasilitas' => 'integer',
        'total_aktivitas' => 'integer',
        'fasilitas_list' => 'array',
        'aktivitas_list' => 'array',
        'foto_list' => 'array',
    ];

    public function getDaftarFotoAttribute(): array
    {
        $list = $this->foto_list;
        if (is_array($list) && count($list) > 0) {
            return array_values(array_filter($list));
        }

        if (! empty($this->foto)) {
            return [$this->foto];
        }

        return [
            '/images/destinasi/a1-1.jpg',
            '/images/destinasi/a1-2.jpg',
        ];
    }

    public function skorAktivitas(): HasMany
    {
        return $this->hasMany(SkorKesesuaianAktivitas::class);
    }
}
