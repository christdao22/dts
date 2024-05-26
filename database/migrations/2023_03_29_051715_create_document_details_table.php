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
        Schema::create('document_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('document_code');
            $table->string('name_of_client');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('transaction_type')->nullable()->default(0);
            $table->foreignId('terminal_id')->nullable();
            $table->foreignId('document_category_id')->nullable();
            $table->boolean('is_check_by_dm')->default(false);
            $table->timestamps();
            // $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_details');
    }
};
