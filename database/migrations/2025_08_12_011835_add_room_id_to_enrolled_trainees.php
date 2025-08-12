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
        Schema::table('enrolled_trainees', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->nullable()->after('course_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrolled_trainees', function (Blueprint $table) {
            //
        });
    }
};
