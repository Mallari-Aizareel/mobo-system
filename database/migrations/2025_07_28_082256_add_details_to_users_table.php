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
        Schema::table('users', function (Blueprint $table) {
            $table->text('description')->nullable()->after('email');
            $table->unsignedBigInteger('address_id')->nullable()->after('description');
            $table->unsignedBigInteger('gender_id')->nullable()->after('address_id');
            $table->date('birthdate')->nullable()->after('gender_id');
            $table->string('religion')->nullable()->after('birthdate');

            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
