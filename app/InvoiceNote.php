<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class InvoiceNote extends Model
{
    use Loggable;

    protected $fillable = ['invoice_id','customer_id','user_id','customer_name','note','note_by'];

    public function customer(){

		return $this->belongsTo('App\Customer', 'customer_id', 'ext_id');
	}

}
