<?php

namespace App\Models;

use App\Traits\GenerateUrl;
use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory, HasMosque, HasCreatedBy, GenerateUrl;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Info::class, 'category_item_id');
    }
}
