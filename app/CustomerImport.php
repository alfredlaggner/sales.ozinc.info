<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CustomerImport
 *
 * @property int $id
 * @property string|null $license
 * @property int|null $ext_id
 * @property string|null $api_id
 * @property string|null $reference_id
 * @property string|null $name
 * @property string|null $business_name
 * @property string|null $street
 * @property string|null $street2
 * @property string|null $city
 * @property int|null $zip
 * @property string|null $territory
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $license_type
 * @property string|null $T-FINAL_REP
 * @property string|null $user_id
 * @property string|null $expiration
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereApiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereExpiration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereLicenseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereStreet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereTFINALREP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereTerritory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerImport whereZip($value)
 * @mixin \Eloquent
 */
class CustomerImport extends Model
{
    protected $fillable = ['license', 'ext_id', 'api_id', 'reference_id', 'name', 'business_name', 'street', 'street2', 'city', 'zip', 'territory', 'phone', 'license_type','email', 'user_id', 'expiration'];
}
