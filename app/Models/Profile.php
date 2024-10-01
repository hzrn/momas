<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use App\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasMosque, HasCreatedBy, HasUpdatedBy;
    protected $fillable = ['mosque_id', 'title', 'category', 'content', 'created_by', 'updated_by'];


}
