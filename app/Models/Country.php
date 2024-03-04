<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

        /**
     * Search countries with that language available.
     *
     * @param string|null $abbreviation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLanguage($query, $lang = null)
    {
        return $query->select('*')
        ->whereRaw("JSON_EXTRACT(name, '$.\"$lang\"') IS NOT NULL")
        ->orWhereRaw("JSON_EXTRACT(description, '$.\"$lang\"') IS NOT NULL");


    }
}
