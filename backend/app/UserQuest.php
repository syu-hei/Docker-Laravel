<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
	protected $table = 'user_quest';
	public $incrementing = false;
	protected $primaryKey = 'user_id';
	public $timestamps = false;
}
