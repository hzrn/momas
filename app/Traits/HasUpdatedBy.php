<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasUpdatedBy
{
    protected static function boothasUpdatedBy()
    {
        static::updating(function ($model){
            $model->updated_by = auth()->user()->id;
        });

    }

    /**
     * Get the createdBy that owns the HasCreatedBy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
