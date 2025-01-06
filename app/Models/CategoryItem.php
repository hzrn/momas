<?php

namespace App\Models;

use App\Traits\GenerateUrl;
use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory, HasMosque, HasCreatedBy;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class, 'category_item_id');
    }

    protected static function boot()
{
    parent::boot();

    static::deleting(function ($categoryItem) {
        $categoryItem->items()->delete();
    });
}


}
