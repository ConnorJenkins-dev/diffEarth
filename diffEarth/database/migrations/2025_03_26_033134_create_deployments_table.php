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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique(); // Unique identifier for the deployment
            $table->string('name'); // Name of the deployment
            $table->decimal('latitude', 10, 8); // Latitude coordinate
            $table->decimal('longitude', 11, 8); // Longitude coordinate
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });

        // Add indexes for faster querying
        Schema::table('deployments', function (Blueprint $table) {
            $table->index('name');
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
