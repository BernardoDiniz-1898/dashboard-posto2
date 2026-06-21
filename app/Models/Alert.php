<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['type', 'message', 'resolved'];

    protected function casts(): array
    {
        return [
            'resolved' => 'boolean',
        ];
    }
}
