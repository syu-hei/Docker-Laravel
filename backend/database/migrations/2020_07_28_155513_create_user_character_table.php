<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCharacterTable extends Migration
{
    public function up()
    {
        Schema::create('user_character', function (Blueprint $table) {
            $table->increments('id');
			$table->string('user_id', 37)->charset('utf8');
			$table->unsignedInteger('character_id')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_character');
    }
}
