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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->morphs('schedulable'); // e.g. Restaurant, BeautyCenter
            $table->tinyInteger('day_of_week'); // 1 = Monday, 7 = Sunday
            $table->time('opening_time');
            $table->time('closing_time');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            // Prevent duplicate day for the same center
            $table->unique(['schedulable_id', 'schedulable_type', 'day_of_week'], 'schedulable_day_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
