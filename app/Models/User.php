<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Trebol\Entrust\Traits\EntrustUserTrait;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash as FacadesHash;

class User extends BaseModel implements AuthenticatableContract, JWTSubject
{
	use Notifiable, EntrustUserTrait, Authenticatable, HasFactory;

	protected $default = ["xid", "name", "profile_image"];

	protected $guarded = ['id', 'warehouse_id', 'opening_balance', 'opening_balance_type', 'credit_limit', 'credit_period', 'created_at', 'updated_at'];

	protected $dates = ['last_active_on'];

	protected $hidden = ['id', 'role_id',  'warehouse_id', 'password', 'remember_token'];

	protected $appends = ['xid', 'x_role_id', 'profile_image_url'];

	protected $filterable = ['name', 'user_type', 'email', 'status', 'phone'];

	protected $hashableGetterFunctions = [
		'getXRoleIdAttribute' => 'role_id',
		'getXWarehouseIdAttribute' => 'warehouse_id',
	];

	protected $casts = [
		'role_id' => Hash::class . ':hash',
		'warehouse_id' => Hash::class . ':hash',
	];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('type', function (Builder $builder) {
			$builder->where('users.user_type', '=', 'staff_members');
		});
	}

	public function setPasswordAttribute($value)
	{
		if ($value) {
			$this->attributes['password'] = FacadesHash::make($value);
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
		$this->attributes['user_type'] = 'staff_members';
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
		return $this->hasOne(UserDetails::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}
}
