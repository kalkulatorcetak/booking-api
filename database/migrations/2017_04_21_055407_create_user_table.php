<?php

use app\Database\Blueprint;
use App\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('users');
    }
}
