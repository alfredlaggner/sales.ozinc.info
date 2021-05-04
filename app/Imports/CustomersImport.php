<?php

namespace App\Imports;

use App\CustomerImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
/*        $phone = '';
        if (strlen(trim($row['phone'])) == 10) {
            $phone = "(" . substr($row['phone'], 0, 3) . ") " . substr($row['phone'], 3, 3) . "-" . substr($row['phone'], 6);
        }*/

        return new CustomerImport([
            'license' => $row['license'],
            'ext_id' => $row['id'],
            'api_id' => $row['api_id'],
            'reference_id' => $row['reference_id'],
            'name' => $row['name'],
            'business_name' => $row['business_name'],
            'street' => $row['street'],
            'street2' => $row['street2'],
            'city' => $row['city'],
            'zip' => $row['zip'],
            'territory' => $row['territory'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'license_type' => $row['license_type'],
            'user_id' => $row['user_id'],
            'expiration' => Date::excelToDateTimeObject($row['expiration']),
        ]);

    }

}
