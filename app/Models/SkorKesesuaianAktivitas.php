<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu baris = skor kesesuaian aktivitas (C6) satu destinasi untuk satu minat.
 */
class SkorKesesuaianAktivitas extends Model
{
    protected $table = 'skor_kesesuaian_aktivitas';

    protected $fillable = ['destinasi_id', 'minat', 'skor'];

    public function destinasi(): BelongsTo
    {
        return $this->belongsTo(Destinasi::class);
    }
}
