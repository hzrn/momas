<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    protected $fillable = ['name', 'address', 'phone_num', 'email', 'total_amount'];

    public function cashflows()
    {
        return $this->hasMany(Cashflow::class);
    }

    public function categoryinfos()
    {
        return $this->hasMany(CategoryInfo::class);
    }

    
}
