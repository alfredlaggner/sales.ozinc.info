<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenNinetyCommission extends Model
{
    protected $table = 'ten_ninety_commissions';
    protected $fillable = ['rep_id', 'month', 'year', 'half','is_comm_paid','volume_collected', 'goal', 'is_ten_ninety'];

    function rep()
    {
    return $this->hasOne('App\SalesPerson','sales_person_id','rep_id');
    }
}
