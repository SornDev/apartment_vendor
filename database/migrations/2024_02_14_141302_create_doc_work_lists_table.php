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
        Schema::create('doc_work_lists', function (Blueprint $table) {
            $table->id();
            $table->string('doc_work_id');
            $table->string('doc_id');
            $table->string('doc_copy')->nullable();
            $table->string('notice')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_work_lists');
    }
};
