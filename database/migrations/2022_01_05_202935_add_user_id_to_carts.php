<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
                    // ↓ 製作外鍵               ↓ 綁定哪個資料表
            $table->foreignId('user_id')->constrained('users')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            // 外鍵的 dropcolumn 要一併去除
            $table->dropConstrainedForeignId('user_id');
        });
    }
}
