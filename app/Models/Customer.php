<?php

namespace App\Models;

use App\Classes\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Trebol\Entrust\Traits\EntrustUserTrait;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Customer extends BaseModel implements AuthenticatableContract, JWTSubject
{
	use Notifiable, EntrustUserTrait, Authenticatable, HasFactory;

	protected  $table = 'users';

	protected $default = ["xid", "name", "profile_image"];

	protected $guarded = ['id', 'warehouse_id', 'opening_balance', 'opening_balance_type', 'credit_limit', 'credit_period', 'role_id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'role_id', 'password', 'remember_token'];

	protected $appends = ['xid', 'profile_image_url'];

	protected $filterable = ['name', 'user_type', 'email', 'status', 'phone'];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('type', function (Builder $builder) {
			$builder->where('users.user_type', '=', 'customers');
		});
	}

	public function setPasswordAttribute($value)
	{
		if ($value) {
			$this->attributes['password'] = Hash::make($value);
		}
	}

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
	}

	public function setUserTypeAttribute($value)
	{
		$this->attributes['user_type'] = 'customers';
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
