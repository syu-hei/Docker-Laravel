<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterCharacterTable extends Migration
{
    public function up()
    {
        Schema::create('master_character', function (Blueprint $table) {
			$table->unsignedInteger('character_id', 0);
			$table->string('asset_id');
			$table->string('character_name', 32)->charset('utf8');
			$table->unsignedInteger('rarity')->default(0);
			$table->unsignedInteger('type')->default(0);
			$table->unsignedInteger('sell_point')->default(0);
			$table->primary('character_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_character');
    }
}
