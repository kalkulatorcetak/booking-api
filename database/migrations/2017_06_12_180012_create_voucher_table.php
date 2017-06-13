<?php

use App\Api\V1\Enums\VoucherType;
use App\Database\Blueprint;
use App\Database\Migration;

class CreateVoucherTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cassa_id');
            $table->enum('type', VoucherType::listValues());
            $table->date('date');
            $table->string('number')->unique();
            $table->double('amount');
            $table->integer('cashier_id');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema->drop('vouchers');
    }
}
