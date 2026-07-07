<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_stats', function (Blueprint $table) {
            $table->id();
            $table->string('stat_key')->unique();   // e.g. cases_handled, student_advisors, years_serving, avg_response
            $table->string('label');                // display caption, e.g. "Cases Handled"
            $table->string('suffix')->nullable();    // e.g. "+", "h"
            $table->string('manual_value')->nullable(); // used when is_auto = false
            $table->boolean('is_auto')->default(true);  // true = compute live from DB
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_stats');
    }
};
