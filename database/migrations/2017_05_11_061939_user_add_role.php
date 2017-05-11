<?php

use app\Database\Blueprint;
use App\Database\Migration;

class UserAddRole extends Migration
{
    public function up()
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->string('roles')->nullable();
        });
    }

    public function down()
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
}
