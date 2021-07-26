<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name', 255)->index()->nullable();
            $table->string('email', 255)->index();
            $table->string('phone', 20)->index()->nullable();
            $table->string('password', 32)->nullable();
            $table->tinyInteger('status')->index()->default(0);
            $table->integer('last_login_time')->default(0);
            $table->string('avatar', 255)->default('');
            $table->integer('time_during_system')->default(60);
            $table->integer('created');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
