<?php


namespace App\Models;

use Examyou\RestAPI\ApiModel;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class BaseModel extends ApiModel
{

	function __call($method, $arguments)
	{
		if (isset($this->hashableGetterFunctions) && isset($this->hashableGetterFunctions[$method])) {

			$value = $this->{$this->hashableGetterFunctions[$method]};

			if ($value) {
				$value = Hashids::encode($value);
			}

			return $value;
		}

		if (isset($this->hashableGetterArrayFunctions) && isset($this->hashableGetterArrayFunctions[$method])) {

			$value = $this->{$this->hashableGetterArrayFunctions[$method]};

			if (count($value) > 0) {
				$valueArray = [];

				foreach ($value as $productId) {
					$valueArray[] = Hashids::encode($productId);
				}

				$value = $valueArray;
			}

			return $value;
		}

		return parent::__call($method, $arguments);
	}

	public function getXIDAttribute()
	{
		return Hashids::encode($this->id);
	}
}
