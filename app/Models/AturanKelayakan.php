<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanKelayakan extends Model
{
    protected $table = 'filter_threshold';

    protected $fillable = ['minat', 'kriteria', 'operator', 'threshold', 'keterangan'];

    protected $casts = ['threshold' => 'integer'];
}
