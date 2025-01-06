<?php

namespace App\Models;

use App\Traits\GenerateUrl;
use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategoryInfo extends Model
{
    use HasFactory;
    use HasMosque, HasCreatedBy;

    protected $guarded = [];

    public function infos()
    {
        return $this->hasMany(Info::class, 'category_info_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($categoryInfo) {
            $categoryInfo->infos()->delete();
        });
    }

}
