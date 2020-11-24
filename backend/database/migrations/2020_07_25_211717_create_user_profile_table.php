<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration 
{
    public function up() {
        Schema::create('user_profile', function (Blueprint $table) {
			$table->string('user_id', 37)->charset('utf8');
			$table->string('user_name', 32)->charset('utf8');
			$table->unsignedInteger('crystal')->default(0);
			$table->unsignedInteger('crystal_free')->default(0);
			$table->unsignedInteger('friend_coin')->default(0);
			$table->unsignedSmallInteger('tutorial_progress')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->primary('user_id');
        });
    }

    public function down() {
        Schema::dropIfExists('user_profile');
    }
}