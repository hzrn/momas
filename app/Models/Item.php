<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    use HasMosque, HasCreatedBy;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(CategoryItem::class, 'category_item_id');
    }
}
