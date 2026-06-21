<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionRecord extends Model
{
    protected $fillable = ['chicken_count', 'detected_at'];

    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
        ];
    }
}
