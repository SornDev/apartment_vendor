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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('rec_id');
            $table->string('doc_work_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('quo_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_tel')->nullable();
            $table->string('customer_address')->nullable();
            $table->integer('rec_discount')->nullable();
            $table->integer('rec_vat')->nullable();
            $table->string('status');
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
