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
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->primary('user_id');
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
        Schema::dropIfExists('user_login');
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