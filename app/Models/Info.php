<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use App\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{

    use HasMosque, HasCreatedBy, HasFactory, HasUpdatedBy;

    protected $fillable = ['mosque_id', 'category_info_id', 'title', 'date', 'content', 'photo', 'created_by', 'updated_by'];


    public function category()
    {
        return $this->belongsTo(CategoryInfo::class, 'category_info_id');
    }




}
