<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterGachaCharacterTable extends Migration
{
    public function up()
    {
        Schema::create('master_gacha_character', function (Blueprint $table) {
			$table->unsignedInteger('gacha_id')->default(0);
			$table->unsignedInteger('character_id')->default(0);
			$table->unsignedInteger('weight')->default(0);
			$table->primary(array('gacha_id', 'character_id'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_gacha_character');
    }
}
