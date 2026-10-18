<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Aturan kelayakan: batas minimal skor C6 per minat.
 * Default: destinasi dengan skor C6 < 2 tidak diikutkan dalam perangkingan.
 */
class AturanKelayakan extends Model
{
    protected $table = 'aturan_kelayakan';

    protected $fillable = ['minat', 'kriteria', 'operator', 'threshold', 'keterangan'];

    protected $casts = ['threshold' => 'integer'];
}
