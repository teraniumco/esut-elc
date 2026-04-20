<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content'); // Rich HTML content
            $table->text('excerpt')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('helpful_yes')->default(0);
            $table->unsignedInteger('helpful_no')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'faq_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_articles');
    }
};
