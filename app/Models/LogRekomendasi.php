<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Riwayat rekomendasi yang ditampilkan sistem.
 * Satu permintaan rekomendasi menghasilkan 5 baris (satu per destinasi teratas).
 */
class LogRekomendasi extends Model
{
    protected $table = 'log_rekomendasi';

    public $timestamps = false; // hanya punya created_at

    protected $fillable = [
        'sesi_id', 'minat', 'anggaran', 'jarak',
        'destinasi_id', 'peringkat', 'nilai_v',
    ];

    protected $casts = ['nilai_v' => 'float'];

    public function destinasi(): BelongsTo
    {
        return $this->belongsTo(Destinasi::class);
    }
}
