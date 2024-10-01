<?php

namespace App\Models;

use App\Traits\HasCreatedBy;
use App\Traits\HasMosque;
use App\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    use HasMosque, HasCreatedBy, HasFactory, HasUpdatedBy;

    protected $fillable = [
        'mosque_id',
        'date',
        'category',
        'description',
        'type',
        'amount',
        'photo',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y'
    ];

}

