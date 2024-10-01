<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    use HasMosque, HasCreatedBy;

    protected $guarded = [];
    protected $contentName = 'content';

    public function category()
    {
        return $this->belongsTo(CategoryInfo::class, 'category_info_id');
    }
    



}
