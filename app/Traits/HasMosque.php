<?php

namespace App\Traits;

use App\Models\Mosque;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasMosque
{
    protected static function boothasMosque()
    {
        static::creating(function ($model){
            $model->mosque_id = auth()->user()->mosque_id;
        });
        
    }

    public function scopeMosqueUser($q)
    {
        return $q->where('mosque_id', auth()->user()->mosque_id);
    }

    /**
     * Get the mosque that owns the HasMosque
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
}
