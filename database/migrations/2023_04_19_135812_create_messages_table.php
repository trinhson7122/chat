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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_message_id')->constrained('list_message_with_mes')->onDelete('cascade');
            $table->string('message');
            $table->boolean('is_file')->default(0);
            $table->boolean('is_image')->default(0);
            $table->boolean('is_group')->default(0);
            $table->boolean('is_removed')->default(0);
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->nullable()->constrained('users');
            $table->foreignId('to_group_id')->nullable()->constrained('group_messages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
