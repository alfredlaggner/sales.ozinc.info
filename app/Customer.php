<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use App\Invoice;
use App\Payment;
class Customer extends Model
{
    use Notifiable;
    use Searchable;
    public $asYouType = true;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    /*    public function toSearchableArray()
        {
            $array = $this->toArray();

            // Customize array...

            return $array;
        }*/

    public function sales_lines()
    {
        return $this->hasMany(SaleInvoice::class, 'customer_id', 'ext_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'customer_id', 'ext_id')
            ->orderBy('invoice_date','desc');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'customer_id', 'ext_id')
            ->orderBy('payments.payment_date','desc');
    }

    public function notes()
    {
        return $this->hasMany(InvoiceNote::class, 'ext_id', 'customer_id');
    }
    public function bcc()
    {
        return $this->hasOne(BccAllLicense::class, 'licenseNumber', 'license');
    }
}
