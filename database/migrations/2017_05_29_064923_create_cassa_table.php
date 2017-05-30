<?php

use App\Api\V1\Enums\CassaAccessType;
use App\Api\V1\Enums\Currency;
use App\Database\Blueprint;
use App\Database\Migration;

class CreateCassaTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('cassas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('currency', Currency::listValues());
            $table->timestamps();
        });

        $this->schema->create('cassa_user', function (Blueprint $table) {
            $table->integer('cassa_id');
            $table->integer('user_id');
            $table->enum('access_type', CassaAccessType::listValues());
            $table->unique(['cassa_id', 'user_id']);
        });
    }

    public function down(): void
    {
        $this->schema->drop('cassas');
        $this->schema->drop('cassa_user');
    }
}
