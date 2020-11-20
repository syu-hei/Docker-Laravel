<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQuestTable extends Migration
{
    public function up() {
        Schema::create('user_quest', function (Blueprint $table) {
			$table->string('user_id', 37)->charset('utf8');
			$table->unsignedInteger('quest_id')->default(0);
			$table->unsignedTinyInteger('status')->default(0);
			$table->unsignedInteger('score')->default(0);
			$table->unsignedInteger('clear_time')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->primary(array('user_id', 'quest_id'));
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_quest');
    }
}
