<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_values',
        'new_values',
        'callback_url',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getTypeAttribute()
    {
        return $this->old_values == null ? 'create' : 'update';
    }
}
