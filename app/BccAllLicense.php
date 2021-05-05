<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BccAllLicense extends Model
{

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


    public function ozCustomer()
    {
        return $this->hasOne(Customer::class, 'license', 'licenseNumber');
    }

    public function ozRegion()
    {
        return $this->hasOne(BccZipToRegion::class, 'zip', 'premiseZip');
    }
}
