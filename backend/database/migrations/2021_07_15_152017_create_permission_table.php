<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name', 255);
            $table->string('router', 255)->index()->comment('tên action hoặc tên api');
            $table->tinyInteger('type')->index()->default(0)->comment('Kiểu: 0: action, 1: api');
            $table->tinyInteger('status')->index()->default(0);
            $table->text('note');
            $table->tinyInteger('is_public')->index()->default(0);
            $table->integer('time_created');
            $table->integer('time_updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission');
    }
}
