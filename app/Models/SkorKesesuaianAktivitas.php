<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkorKesesuaianAktivitas extends Model
{
    protected $table = 'skor_kesesuaian_aktivitas';

    protected $fillable = ['destinasi_id', 'minat', 'skor'];
}
