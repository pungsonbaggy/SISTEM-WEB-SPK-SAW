<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bobot kriteria untuk satu nilai segmen profil.
 * Contoh baris: segmen='minat', nilai='Diving', w1..w6 = 0.0952 ... 0.1941
 */
class BobotSegmen extends Model
{
    protected $table = 'bobot_segmen';

    protected $fillable = ['segmen', 'nilai', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6'];

    /** Kembalikan enam bobot sebagai array angka. */
    public function sebagaiArray(): array
    {
        return [
            (float) $this->w1, (float) $this->w2, (float) $this->w3,
            (float) $this->w4, (float) $this->w5, (float) $this->w6,
        ];
    }
}
