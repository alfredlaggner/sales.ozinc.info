<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentAr extends Model
{
    protected $fillable = [
        'comm_percent',
        'commission',
        'comm_paid_at',
        'comm_paid_by',
        'is_comm_paid',
        'is_ten_ninety',
        'half',
        '1099_calendar_id'
    ];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'ext_id');
    }
}
