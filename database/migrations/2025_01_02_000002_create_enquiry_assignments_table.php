<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiry_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('advisor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->text('assignment_notes')->nullable();
            $table->timestamp('assigned_at');
            $table->boolean('is_active')->default(true); // false when reassigned
            $table->timestamps();

            $table->index(['enquiry_id', 'is_active']);
            $table->index('advisor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_assignments');
    }
};
