<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotSegmen extends Model
{
    protected $table = 'bobot_segmen';

    protected $fillable = ['segmen', 'nilai', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6'];

    public function sebagaiArray(): array
    {
        return [
            (float) $this->w1, (float) $this->w2, (float) $this->w3,
            (float) $this->w4, (float) $this->w5, (float) $this->w6,
        ];
    }
}
