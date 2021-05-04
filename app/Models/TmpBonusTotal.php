<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmpBonusTotal extends Model
{
    protected $fillable = ['sales_order', 'amount','rep_id'];
}
