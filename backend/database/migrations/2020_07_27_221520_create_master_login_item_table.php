<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterLoginItemTable extends Migration
{
    public function up() {
        Schema::create('master_login_item', function (Blueprint $table) {
			$table->unsignedInteger('login_day', 0);
			$table->unsignedInteger('item_type', 0);
			$table->unsignedInteger('item_count', 0);
			$table->primary('login_day');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_login_item');
    }
}
