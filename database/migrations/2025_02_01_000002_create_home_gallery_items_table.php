<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('height')->nullable(); // px, for masonry variation
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_gallery_items');
    }
};
