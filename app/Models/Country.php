<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($model) {
            // Log the updates using the custom log channel
            Log::channel('updates')->info('Model updated', [
                'id' => $model->id,
                'changes' => $model->getDirty(),
            ]);
        });
    }
}
