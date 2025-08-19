<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('resumes', function (Blueprint $table) {
            $table->json('parsed_skills')->nullable()->after('skills');
            $table->json('parsed_courses')->nullable()->after('parsed_skills');
            $table->json('parsed_experience')->nullable()->after('parsed_courses');
        });
    }
    public function down(): void {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn(['parsed_skills','parsed_courses','parsed_experience']);
        });
    }
};
