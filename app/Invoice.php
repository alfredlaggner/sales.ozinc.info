<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;


class Invoice extends Model
{
    use Loggable;
    use LaravelSubQueryTrait;
    protected $table = 'invoices';

    public function customer(){
        return ($this->belongsTo(Customer::class,'customer_id','ext_id'));
    }
    public function salesorder(){
        return ($this->hasOne(SalesOrder::class,'sales_order','sales_order'));
    }

}
