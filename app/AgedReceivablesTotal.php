<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Laravel\Scout\Searchable;
    use Haruncpi\LaravelUserActivity\Traits\Loggable;

	class AgedReceivablesTotal extends Model
	{
		use Searchable;
		use Loggable;
		public $asYouType = true;

        protected $fillable = ['is_felon'];

		public function toSearchableArray()
		{
			$array = $this->toArray();

			return $array;
		}

		public function receivables()
		{
			return $this->hasMany('App\AgedReceivable', 'customer_id', 'customer_id');
		}

        public function scopeMinbalance($query)
        {
            return $query->where('residual', '>', 100);
        }

	}
