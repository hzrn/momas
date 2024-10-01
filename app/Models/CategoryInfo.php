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
    use HasMosque, HasCreatedBy, GenerateUrl;

    protected $guarded = [];

    public function infos()
    {
        return $this->hasMany(Info::class, 'category_info_id');
    }
}
