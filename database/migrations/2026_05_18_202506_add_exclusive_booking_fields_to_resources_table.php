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
        Schema::table('resources', function (Blueprint $table) {
            $table->string('resource_type')->default('shared'); // Defaults to shared for backwards compatibility
            $table->integer('unit_count')->nullable();
            $table->integer('unit_capacity')->nullable();
            $table->integer('max_guests_per_booking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn([
                'resource_type',
                'unit_count',
                'unit_capacity',
                'max_guests_per_booking'
            ]);
        });
    }
};
