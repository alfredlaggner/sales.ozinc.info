<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestHorizon extends Model
{
    protected $fillable = ['sales_person_id','sales_order','invoice_id','commission', 'percent'];
}
