<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;
class StockPicking extends Model
{
    use LaravelSubQueryTrait;

    public function customer(){
        return $this->belongsTo(Customer::class,'partner_id','ext_id');
    }
    public function salesorder(){
        return $this->belongsTo(SalesOrder::class,'salesorder_number','sales_order');
    }
}
