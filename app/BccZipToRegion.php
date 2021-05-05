<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BccZipToRegion extends Model
{
    public function bccAllLicense()
    {
        return $this->belongsTo(BccAllLicense::class, 'premiseZip','zip');
    }
}
