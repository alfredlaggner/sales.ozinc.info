<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;



class Invoice extends Model
{
    use Loggable;
    protected $table = 'invoices';

    public function customer(){
        return ($this->belongsTo(Customer::class,'customer_id','ext_id'));
    }
}
