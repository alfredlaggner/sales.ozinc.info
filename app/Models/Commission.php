<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Commission
 *
 * @property int $id
 * @property int $margin
 * @property float|null $commission
 * @property string $region
 * @property string $version
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereVersion($value)
 * @mixin \Eloquent
 */
class Commission extends Model
{
    //
}
