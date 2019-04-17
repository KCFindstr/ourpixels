<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	protected $primaryKey = 'UserId';

	protected $table = 'Users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'Username', 'Password'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'Password', 'Token'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime'
	];

	public function getAuthPassword() {
		return $this->Password;
	}

	public function getRememberToken() {
			return $this->Token;
	}

	public function setRememberToken($value) {
			$this->Token = $value;
	}

	public function getRememberTokenName() {
			return 'Token';
	}

	public function artworks() {
		return $this->hasMany('App\Image', 'CreatorId');
	}

	public function images() {
		return $this->belongsToMany('App\Image', 'Access', 'UserId', 'ImageId');
	}
}
