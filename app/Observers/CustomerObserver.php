<?php

namespace App\Observers;

use App\Classes\Common;
use App\Models\Customer;
use Illuminate\Support\Facades\File;

class CustomerObserver
{
	public function updating(Customer $user)
	{
		$original = $user->getOriginal();
		if ($user->isDirty('image')) {
			$userImagePath = Common::getFolderPath('userImagePath');

			File::delete($userImagePath . $original['image']);
		}
	}

	public function deleting(Customer $user)
	{
		$userImagePath = Common::getFolderPath('userImagePath');

		File::delete($userImagePath . $user->image);
	}
}
