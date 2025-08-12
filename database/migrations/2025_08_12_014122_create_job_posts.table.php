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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to users table (agency)
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('job_position');
            $table->text('job_description');
            $table->text('job_qualifications')->nullable();
            $table->text('job_benefits')->nullable();
            $table->string('job_location');
            $table->string('job_salary')->nullable();
            $table->string('job_schedule')->nullable();

            // Foreign key to job_types table
            $table->unsignedBigInteger('job_type_id');
            $table->foreign('job_type_id')->references('id')->on('job_types')->onDelete('cascade');

            // New image column
            $table->string('job_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
