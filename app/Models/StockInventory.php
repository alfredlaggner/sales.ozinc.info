<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInventory extends Model
{
    public function product(){
        return $this->hasOne(ProductProduct::class, 'product_id', 'ext_id');
    }
}
