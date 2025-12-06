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
        Schema::create('swipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swiper_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('swiped_id')->constrained('people')->onDelete('cascade');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();

            // Prevent duplicate swipes
            $table->unique(['swiper_id', 'swiped_id']);

            // Indexes for queries
            $table->index('swiped_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swipes');
    }
};
