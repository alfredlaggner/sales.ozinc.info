<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;

class SalesOrder extends Model
{
    use LaravelSubQueryTrait;

    protected $table = 'salesorders';
    protected $fillable = ['sales_order', 'sales_order_id', 'order_date', 'amount_untaxed', 'amount_total', 'amount_tax', 'deliver_date', 'salesperson_id', 'customer_id'];

    public function saleinvoice()
    {
        return $this->hasMany('App\SaleInvoice', 'order_id', 'sales_order_id');
    }

    public function stock_pickings()
    {
        return $this->hasOne(StockPicking::class, 'sales_order_id', 'salesorder_number');
    }
}
