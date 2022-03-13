<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogErrors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_errors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->text('exception')->nullable()->comment('例外狀況');
            $table->text('message')->nullable()->comment('錯誤訊息');
            $table->integer('line')->nullable()->comment('錯誤訊息第幾行');
            $table->json('trace')->nullable()->comment('追蹤紀錄');
            $table->string('method')->nullable()->comment('路由的方式');
            $table->json('params')->nullable()->comment('傳送參數');
            $table->text('uri')->nullable()->comment('網址');
            $table->text('user_agent')->nullable()->comment('瀏覽器或透過的機器人');
            $table->json('header')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_errors');
    }
}
