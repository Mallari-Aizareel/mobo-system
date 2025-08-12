<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            // Personal Info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('zip_code');

            // Professional Summary
            $table->text('summary');

            // Education
            $table->string('school_name');
            $table->string('degree');
            $table->string('field_of_study');
            $table->year('grad_year');

            // Experience
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->date('job_start_date')->nullable();
            $table->date('job_end_date')->nullable();
            $table->text('job_description')->nullable();

            // Skills
            $table->text('skills');

            // Certifications
            $table->string('certification_name')->nullable();
            $table->year('certification_year')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('resumes');
    }
};
