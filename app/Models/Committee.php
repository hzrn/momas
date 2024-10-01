<?php

namespace App\Models;

use App\Traits\HasUpdatedBy;
use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory, HasMosque, HasCreatedBy, HasUpdatedBy;
    protected $fillable = ['mosque_id', 'name', 'phone_num', 'position', 'address', 'photo', 'created_by', 'updated_by'];

}
