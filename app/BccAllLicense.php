<?php

namespace App;
use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;
use Illuminate\Database\Eloquent\Model;

class BccAllLicense extends Model
{
      use LaravelSubQueryTrait;
    protected $fillable = [
        "licenseNumber",
        "licenseType",
        "issuedDate",
        "addressLine1",
        "addressLine2",
        "premiseCity",
        "premiseState",
        "premiseZip",
        "premiseCounty",
        "licenseStatus",
        "businessStructure",
        "medicinal",
        "adultUse",
        "microActivityRetailerNonStorefront",
        "microActivityRetailer",
        "microActivityDistributor",
        "microActivityDistributorTransportOnly",
        "microActivityLevel1Manufacturer",
        "microActivityCultivator",
        "expiryDate",
        "businessName",
        "businessDBA",
        "businessOwner",
        "website",
        "phone",
        "email"
    ];


    public function customer()
    {
        return $this->hasOne(Customer::class, 'ext_id', 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'customer_id');
    }
    public function last_invoice()
    {
        return $this->hasOne(Invoice::class, 'customer_id', 'customer_id')
            ->orderBy('invoice_date','desc');
    }

    public function ozRegion()
    {
        return $this->hasOne(BccZipToRegion::class, 'zip', 'premiseZip');
    }
/*    public function invoices(){
        return $this->hasManyThrough(Invoice::class, Customer::class, 'ext_id');
    }*/
}
