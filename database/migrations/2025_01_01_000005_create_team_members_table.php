<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');                     // e.g. "Student Advisor", "Supervising Lecturer"
            $table->string('level')->nullable();        // e.g. "500L", "Faculty"
            $table->string('photo_path')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', ['lecturer', 'student'])->default('student');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
