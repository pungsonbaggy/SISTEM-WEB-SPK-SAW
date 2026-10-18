<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Destinasi = tabel alternatif (A1..A15).
 *
 * Eloquent adalah "penerjemah" antara tabel database dan objek PHP.
 * Dengan $fillable, kita mengizinkan kolom tertentu diisi massal (create/update).
 */
class Destinasi extends Model
{
    protected $table = 'destinasi';

    protected $fillable = [
        'kode', 'nama', 'deskripsi',
        'c1_jarak', 'c2_biaya',
        'c3_fasilitas', 'c4_keindahan', 'c5_kebersihan',
        'kunjungan_2025',
    ];

    protected $casts = [
        'c1_jarak' => 'float',
        'c2_biaya' => 'float',
        'c3_fasilitas' => 'integer',
        'c4_keindahan' => 'integer',
        'c5_kebersihan' => 'integer',
        'kunjungan_2025' => 'integer',
    ];

    /** Semua skor C6 milik destinasi ini (empat baris, satu per minat). */
    public function skorAktivitas(): HasMany
    {
        return $this->hasMany(SkorKesesuaianAktivitas::class);
    }

    /** Ambil skor C6 untuk satu minat, misal 'Diving'. */
    public function skorUntuk(string $minat): ?int
    {
        return $this->skorAktivitas
            ->firstWhere('minat', $minat)?->skor;
    }
}
