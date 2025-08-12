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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['enrolled_trainee_id']);
            $table->dropColumn('enrolled_trainee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('enrolled_trainee_id')->nullable();
            $table->foreign('enrolled_trainee_id')->references('id')->on('enrolled_trainees')->onDelete('cascade');
        });
    }
};
