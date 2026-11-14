<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogRekomendasi extends Model
{
    protected $table = 'log_rekomendasi';

    public $timestamps = false;

    protected $fillable = [
        'sesi_id', 'minat', 'anggaran', 'jarak',
        'destinasi_id', 'peringkat', 'nilai_v',
    ];

    protected $casts = ['nilai_v' => 'float'];
}
