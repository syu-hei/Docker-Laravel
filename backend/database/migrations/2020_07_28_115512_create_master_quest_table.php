<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterQuestTable extends Migration {

    public function up()
    {
        Schema::create('master_quest', function (Blueprint $table) {
			$table->unsignedInteger('quest_id')->default(0);
			$table->string('quest_name')->charset('utf8');
            $table->timestamp('open_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('close_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->unsignedInteger('item_type')->default(0);
			$table->unsignedInteger('item_count')->default(0);
			$table->primary('quest_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_quest');
    }
}
