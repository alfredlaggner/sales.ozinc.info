<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedCommission extends Model
{
  //  protected $table = 'ten_ninety_saved_commissions';
    protected $fillable = ['is_commissions_paid','start','end'];
}
