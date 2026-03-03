<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->boolean('has_terrace')->default(false);
            $table->boolean('smoking_area')->default(false);
            $table->boolean('wheelchair_access')->default(false);

            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            $table->boolean('online_booking')->default(true);

            $table->decimal('rating_average', 2, 1)->default(0);
            $table->integer('rating_count')->default(0);

            $table->foreignId('interest_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
