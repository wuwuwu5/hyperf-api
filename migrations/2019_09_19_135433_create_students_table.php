<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname', 50)->nullable()->default(null)->comment('用户昵称');
            $table->string('real_name', 50)->nullable()->default(null)->comment('真实姓名');
            $table->string('avatar', 300)->nullable()->comment('用户头像');
            $table->tinyInteger('gender')->nullable()->default(0)->comment('用户性别，0-未知，1-男，2-女');
            $table->string('openid', 100)->comment('微信openid');
            $table->string('unionid', 100)->comment('微信unionid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}
