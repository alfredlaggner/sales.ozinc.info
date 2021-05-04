<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CommissionsPaid
 *
 * @property int $id
 * @property int|null $ext_id
 * @property string $order_number
 * @property int|null $saved_commissions_id
 * @property int $is_paid
 * @property string|null $paid_at
 * @property string|null $paid_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Salesline|null $salesline
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereSavedCommissionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionsPaid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommissionsPaid extends Model
{
    protected $fillable = ['saved_commissions_id','ext_id','is_paid','paid_at','paid_by'];

    public function salesline(){

        return $this->hasOne('App\Salesline', 'ext_id', 'ext_id');
    }
}
