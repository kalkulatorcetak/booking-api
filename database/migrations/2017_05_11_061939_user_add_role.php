<?php

use App\Database\Blueprint;
use App\Database\Migration;

class UserAddRole extends Migration
{
    public function up(): void
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->string('roles')->nullable();
        });
    }

    public function down(): void
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
}
