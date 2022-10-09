<?php

namespace App\Models;

use App\Classes\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Supplier extends BaseModel
{
	use Notifiable;

	protected  $table = 'users';

	protected $default = ["xid", "name", "profile_image"];

	protected $guarded = ['id', 'warehouse_id', 'opening_balance', 'opening_balance_type', 'credit_limit', 'credit_period', 'role_id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'role_id', 'password', 'remember_token'];

	protected $filterable = ['name', 'user_type', 'email', 'status', 'phone', 'due_amount'];

	protected $appends = ['xid', 'profile_image_url'];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('type', function (Builder $builder) {
			$builder->where('users.user_type', '=', 'suppliers');
		});
	}

	public function setUserTypeAttribute($value)
	{
		$this->attributes['user_type'] = 'suppliers';
	}

	public function setRoleIdAttribute($value)
	{
		$this->attributes['role_id'] = null;
	}

	public function getProfileImageUrlAttribute()
	{
		$userImagePath = Common::getFolderPath('userImagePath');

		return $this->profile_image == null ? asset('images/user.png') : Common::getFileUrl($userImagePath, $this->profile_image);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function details()
	{
		return $this->hasOne(UserDetails::class, 'user_id', 'id');
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}
}
