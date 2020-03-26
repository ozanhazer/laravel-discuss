<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInitialTables extends Migration
{
    public function up()
    {
        Schema::create(config('discuss.table_prefix') . '_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order')->default(1);
            $table->string('name');
            $table->string('color', 20);
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create(config('discuss.table_prefix') . '_threads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('category_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id');
            $table->boolean('sticky')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('post_count')->default(0);
            $table->timestamps();
            $table->timestamp('last_post_at')->nullable();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')
                ->on(config('discuss.table_prefix') . '_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                ->on(config('discuss.user_model'))
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create(config('discuss.table_prefix') . '_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('thread_id')->references('id')
                ->on(config('discuss.table_prefix') . '_threads')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')->references('id')
                ->on(config('discuss.user_model'))
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create(config('discuss.table_prefix') . '_followed_threads', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('thread_id');
            $table->primary(['user_id', 'thread_id']);

            $table->foreign('thread_id')->references('id')
                ->on(config('discuss.table_prefix') . '_threads')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')->references('id')
                ->on(config('discuss.user_model'))
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('discuss.table_prefix') . '_followed_threads');
        Schema::dropIfExists(config('discuss.table_prefix') . '_posts');
        Schema::dropIfExists(config('discuss.table_prefix') . '_threads');
        Schema::dropIfExists(config('discuss.table_prefix') . '_categories');
    }
}
