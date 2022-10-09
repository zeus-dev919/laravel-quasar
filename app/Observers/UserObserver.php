<?php

namespace App\Observers;

use App\Classes\Common;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserObserver
{
	public function updating(User $user)
	{
		$original = $user->getOriginal();
		if ($user->isDirty('image')) {
			$userImagePath = Common::getFolderPath('userImagePath');

			File::delete($userImagePath . $original['image']);
		}
	}

	public function deleting(User $user)
	{
		$userImagePath = Common::getFolderPath('userImagePath');

		File::delete($userImagePath . $user->image);
	}
}
