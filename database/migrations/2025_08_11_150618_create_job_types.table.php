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
        Schema::create('job_types', function (Blueprint $table) {
            $table->id();
            $table->boolean('full_time')->default(false);
            $table->boolean('part_time')->default(false);
            $table->boolean('hybrid')->default(false);
            $table->boolean('remote')->default(false);
            $table->boolean('on_site')->default(false);
            $table->boolean('urgent')->default(false);
            $table->boolean('open_for_fresh_graduates')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_types');
    }
};
