<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussTables extends Migration
{

    private $userModelTableName;

    private $userModelPK;

    public function up()
    {
        $userClassName = config('discuss.user_model');

        /** @var \Illuminate\Foundation\Auth\User $userModel */
        $userModel = new $userClassName;

        $this->userModelPK        = $userModel->getKeyName();
        $this->userModelTableName = $userModel->getTable();

        Schema::create($this->prefixTable('categories'), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order')->default(1);
            $table->string('name');
            $table->string('color', 20)->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create($this->prefixTable('threads'), function (Blueprint $table) {
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
                ->on($this->prefixTable('categories'))
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references($this->userModelPK)
                ->on($this->userModelTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create($this->prefixTable('posts'), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('thread_id')->references('id')
                ->on($this->prefixTable('threads'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references($this->userModelPK)
                ->on($this->userModelTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create($this->prefixTable('followed_threads'), function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('thread_id');
            $table->primary(['user_id', 'thread_id']);

            $table->foreign('thread_id')->references('id')
                ->on($this->prefixTable('threads'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references($this->userModelPK)
                ->on($this->userModelTableName)
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->prefixTable('followed_threads'));
        Schema::dropIfExists($this->prefixTable('posts'));
        Schema::dropIfExists($this->prefixTable('threads'));
        Schema::dropIfExists($this->prefixTable('categories'));
    }

    private function prefixTable($tableName)
    {
        return ($tablePrefix = config('discuss.table_prefix')) ?
            $tablePrefix . '_' . $tableName :
            $tableName;
    }
}
