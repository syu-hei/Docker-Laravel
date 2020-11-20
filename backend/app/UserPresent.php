<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPresent extends Model
{
	protected $table = 'user_present';
	public $incrementing = true;
	protected $primaryKey = 'present_id';
	public $timestamps = false;
}
