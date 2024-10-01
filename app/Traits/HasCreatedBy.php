<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCreatedBy
{
    protected static function boothasCreatedBy()
    {
        static::creating(function ($model){
            $model->created_by = auth()->user()->id;
        });
        
    }

    /**
     * Get the createdBy that owns the HasCreatedBy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
