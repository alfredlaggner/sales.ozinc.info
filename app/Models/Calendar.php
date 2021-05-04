<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Calendar
 *
 * @property int $id
 * @property string|null $start
 * @property string|null $end
 * @property string|null $pay_date
 * @property int|null $month
 * @property int|null $half
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereHalf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar wherePayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereStart($value)
 * @mixin \Eloquent
 */
class Calendar extends Model
{
    protected $table = '1099_calendar';
}
