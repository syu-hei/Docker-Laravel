<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginTable extends Migration {
    public function up() {
        Schema::create('user_login', function (Blueprint $table) {
			$table->string('user_id', 37)->charset('utf8');
			$table->unsignedSmallInteger('login_day')->default(0);
            $table->timestamp('last_login_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->primary('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_login');
    }
}