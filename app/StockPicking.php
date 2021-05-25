<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;
class StockPicking extends Model
{
    use LaravelSubQueryTrait;

    public function customer(){
        return $this->hasOne(Customer::class,'ext_id', 'partner_id');
    }
    public function salesorder(){
        return $this->belongsTo(SalesOrder::class,'salesorder_number','sales_order');
    }
/*    public function salesorder(){
        return $this->hasMany(SalesOrder::class,'customer_id','partner_id');
    }*/
}
