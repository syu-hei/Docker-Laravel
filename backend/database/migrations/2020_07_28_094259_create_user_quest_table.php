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
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->primary(array('user_id', 'quest_id'));
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
        Schema::dropIfExists('user_quest');
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
