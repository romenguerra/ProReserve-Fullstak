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
        $tables = ['restaurants', 'sport_centers', 'health_centers', 'beauty_centers', 'leisure_centers'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'wheelchair_access')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('wheelchair_access');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['restaurants', 'sport_centers', 'health_centers', 'beauty_centers', 'leisure_centers'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $default = ($tableName === 'health_centers') ? true : false;
                $table->boolean('wheelchair_access')->default($default);
            });
        }
    }
};
