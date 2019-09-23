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
            $table->timestamp('last_login_time')->nullable()->comment('最后登录时间');
            $table->timestamp('last_read_time')->nullable()->comment('最后阅读时间');
            $table->timestamps();

            $table->index('unionid', 'unionid');
            $table->index('real_name', 'real_name');
            $table->index('nickname', 'nickname');
            $table->index('last_read_time', 'last_read_time');
            $table->index('last_login_time', 'last_login_time');
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
