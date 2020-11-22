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
        // 関数の定義
        DB::connection('public')->statement("
        create or replace function set_update_time() returns trigger language plpgsql as
        $$
          begin
            new.updated_at = CURRENT_TIMESTAMP;
            return new;
          end;
        $$;
        ");
        // トリガーの定義
        DB::connection('public')->statement("
            create trigger update_trigger before update on medias for each row
              execute procedure set_update_time();
        ");
    }

    public function down()
    {
        Schema::dropIfExists('master_gacha');
        // DBと関数とトリガーの削除処理
        Schema::connection('public')->drop('medias');
        DB::connection('public')->statement("
            DROP TRIGGER update_trigger ON medias;
        ");
        DB::connection('public')->statement("
            DROP FUNCTION set_update_time();
        ");
    }
}
