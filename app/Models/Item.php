<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use App\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasMosque, HasCreatedBy, HasFactory, HasUpdatedBy;

    protected $fillable = ['mosque_id', 'category_item_id', 'name', 'description', 'quantity', 'price', 'photo', 'created_by', 'updated_by'];

    public function category()
    {
        return $this->belongsTo(CategoryItem::class, 'category_item_id');
    }
}
