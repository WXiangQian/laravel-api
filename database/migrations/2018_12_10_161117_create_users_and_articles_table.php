<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAndArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户表
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 50)->comment('手机号');
            $table->string('nickname',100)->comment('昵称');
            $table->string('password')->comment('密码');
            $table->timestamps();
        });

        // 文章分类表
        Schema::create('article_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('分类名');
            $table->unsignedInteger('pid')->default(0)->comment('上级id');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        // 文章表
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('type_id')->comment('类型id');
            $table->string('title', 50)->comment('标题');
            $table->text('content')->comment('内容');
            $table->softDeletes();
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
    }
}
