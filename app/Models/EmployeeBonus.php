<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeBonus extends Model
{
    protected $fillable =[
        'bonus',
        'base_bonus',
        'comm_paid_at',
        'comm_paid_by',
        'sales_person_id',
        'sales_person_name',
        'name',
        'year',
        'month',
        'half',
        'is_ten_ninety',
        '1099_calendar_id'
        ];
}
