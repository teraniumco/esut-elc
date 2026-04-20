<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiry_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('advisor_id')->constrained('users')->cascadeOnDelete();
            $table->longText('content');           // Advisor's draft response
            $table->longText('internal_notes')->nullable(); // Notes visible to portal only

            // Review
            $table->enum('review_status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_notes')->nullable(); // Feedback from supervisor
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('sent_at')->nullable();   // When email was dispatched

            $table->boolean('is_current')->default(true); // Latest response for this enquiry
            $table->integer('version')->default(1);       // Revision counter

            $table->timestamps();

            $table->index(['enquiry_id', 'is_current']);
            $table->index(['review_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_responses');
    }
};
