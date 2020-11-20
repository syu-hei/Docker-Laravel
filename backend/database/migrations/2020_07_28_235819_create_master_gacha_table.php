<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterGachaTable extends Migration
{
    public function up()
    {
        Schema::create('master_gacha', function (Blueprint $table) {
			$table->unsignedInteger('gacha_id', 0);
			$table->string('banner_id');
			$table->unsignedInteger('cost_type')->default(0);
			$table->unsignedInteger('cost_amount')->default(0);
			$table->unsignedInteger('draw_count')->default(0);
			$table->timestamp('open_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('close_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('description');
			$table->primary('gacha_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_gacha');
    }
}
