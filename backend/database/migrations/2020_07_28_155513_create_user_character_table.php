<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCharacterTable extends Migration
{
    public function up()
    {
        Schema::create('user_character', function (Blueprint $table) {
                $table->increments('id');
			    $table->string('user_id', 37)->charset('utf8');
			    $table->unsignedInteger('character_id')->default(0);
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
            create trigger update_trigger before update on user_character for each row
              execute procedure set_update_time();
        ");
    }

    public function down()
    {
        Schema::dropIfExists('user_character');
        DB::statement("
        DROP TRIGGER update_trigger ON user_character;
        ");
        DB::statement("
            DROP FUNCTION set_update_time();
        ");
    }
}
