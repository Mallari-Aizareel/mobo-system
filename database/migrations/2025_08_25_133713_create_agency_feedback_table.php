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
        Schema::create('agency_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');   // target agency
            $table->unsignedBigInteger('user_id');     // who gave feedback
            $table->boolean('liked')->default(false);  // like/dislike
            $table->tinyInteger('rating')->nullable(); // 1–5 stars
            $table->timestamps();

            $table->unique(['agency_id', 'user_id']); // prevent duplicate feedback
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_feedback');
    }
};
