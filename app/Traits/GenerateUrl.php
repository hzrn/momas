<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GenerateUrl
{
    public static function bootGenerateUrl()
    {
        static::creating(function ($model) {
            $model->url = Str::slug($model->mosque_id . '-' . $model->name);
        });

        static::updating(function ($model) {
            $model->url = Str::slug($model->mosque_id . '-' . $model->name);
        });
    }
}
