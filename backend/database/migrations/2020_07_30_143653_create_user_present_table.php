<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPresentTable extends Migration
{
    public function up()
    {
        Schema::create('user_present', function (Blueprint $table) {
			$table->string('user_id', 37)->charset('utf8');
			$table->increments('present_id');
			$table->unsignedSmallInteger('item_type')->default(0);
			$table->unsignedInteger('item_count')->default(0);
			$table->string('description', 32)->charset('utf8');
			$table->timestamp('limited_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_present');
    }
}
