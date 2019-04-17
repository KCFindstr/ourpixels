<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	protected $primaryKey = 'ImageId';
	protected $table = 'ImageData';

	public function creator() {
		return $this->belongsTo('App\User', 'CreatorId');
	}

	public function editors() {
		return $this->belongsToMany('App\User', 'Access', 'ImageId', 'UserId');
	}
}
