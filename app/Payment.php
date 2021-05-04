<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use App\Payment;
/**
 * Class News
 * @mixin Eloquent
 */
class Payment extends Model
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
