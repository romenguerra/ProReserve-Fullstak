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
        Schema::create('health_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // clinica estetica, clinica medica, hospital, etc.
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->boolean('has_emergency')->default(false);
            $table->boolean('wheelchair_access')->default(true);
            $table->boolean('has_parking')->default(false);

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
        Schema::dropIfExists('health_centers');
    }
};
