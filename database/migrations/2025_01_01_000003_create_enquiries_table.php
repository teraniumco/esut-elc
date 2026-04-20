<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 20)->unique(); // e.g. ELC-2025-00142
            $table->string('full_name')->nullable();        // nullable for anonymous
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('matter_category');              // enum handled at app level
            $table->text('description');
            $table->enum('urgency', ['normal', 'urgent'])->default('normal');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->enum('status', [
                'received',
                'under_review',
                'in_progress',
                'awaiting_approval',
                'responded',
                'closed',
            ])->default('received');
            $table->text('internal_notes')->nullable();     // not visible to requester
            $table->text('response')->nullable();           // final response dispatched
            $table->timestamp('responded_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['reference_code', 'status']);
            $table->index('matter_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
