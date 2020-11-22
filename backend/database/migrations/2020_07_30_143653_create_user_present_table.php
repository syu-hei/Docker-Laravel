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
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        // 関数の定義
        DB::statement("
        create or replace function set_update_time() returns trigger language plpgsql as
        $$
          begin
            new.updated_at = CURRENT_TIMESTAMP;
            return new;
          end;
        $$;
        ");
        // トリガーの定義
        DB::statement("
            create trigger update_trigger before update on medias for each row
              execute procedure set_update_time();
        ");
    }

    public function down()
    {
        Schema::dropIfExists('user_present');

        // DBと関数とトリガーの削除処理
        Schema::drop('medias');
        DB::statement("
            DROP TRIGGER update_trigger ON medias;
        ");
        DB::statement("
            DROP FUNCTION set_update_time();
        ");
    }
}
