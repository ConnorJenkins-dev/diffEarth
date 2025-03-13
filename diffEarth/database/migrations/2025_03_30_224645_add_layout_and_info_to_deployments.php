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
        Schema::table('deployments', function (Blueprint $table) {
            $table->text('info')->nullable()->after('description');
            $table->json('layout')->nullable()->after('info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deployments', function (Blueprint $table) {
            Schema::table('deployments', function (Blueprint $table) {
                $table->dropColumn(['info', 'layout']);
            });
        });
    }
};
