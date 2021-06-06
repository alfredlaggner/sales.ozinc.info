<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesline extends Model
{
    public function invoice_paid(){

        return $this->hasOne('App\CommissionsPaid', 'ext_id', 'ext_id');
    }
    public function invoice_paid_1099(){

        return $this->hasOne('App\TenNinetyPaid', 'ext_id', 'ext_id');
    }
    public function getCommissionsUnpaidAttribute()
    {
        return $this->invoice_paid()->where('invoice_paid.is_paid', 0)->get();
    }
    public function getTenNinetyUnpaidAttribute()
    {
        return $this->invoice_paid_1099()->where('invoice_paid_1099.is_paid', 0)->get();
    }
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'ext_id');
    }
}
