<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterShopTable extends Migration
{
    public function up()
    {
        Schema::create('master_shop', function (Blueprint $table) {
			$table->string('shop_id');
			$table->unsignedInteger('cost')->default(0);
			$table->unsignedInteger('amount')->default(0);
			$table->primary('shop_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_shop');
    }
}
