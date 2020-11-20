<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCharacter extends Model
{
	protected $table = 'user_character';
	public $incrementing = true;
	protected $primaryKey = 'id';
	public $timestamps = false;
}
