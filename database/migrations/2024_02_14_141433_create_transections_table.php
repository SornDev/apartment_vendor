<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transections', function (Blueprint $table) {
            $table->id();
            $table->string('tran_id');
            $table->string('tran_type');
            $table->string('rec_id')->nullable();
            $table->string('tran_details');
            $table->string('currency_payed')->nullable();
            $table->string('currency')->nullable();
            $table->string('bank_id')->nullable();
            $table->string('rate');
            $table->string('price');
            $table->date('tran_date');
            $table->string('status');
            $table->string('user_id');
            $table->string('fn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transections');
    }
};
