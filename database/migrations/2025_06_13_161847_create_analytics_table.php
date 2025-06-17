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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('eventType')->nullable();
            $table->string('url')->nullable();
            $table->string('path')->nullable();
            $table->string('referrer')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('userId')->nullable();
            $table->string('browserName')->nullable();
            $table->string('browserVersion')->nullable();
            $table->string('os')->nullable();
            $table->string('deviceType')->nullable();
            $table->string('deviceVendor')->nullable();
            $table->string('deviceModel')->nullable();
            $table->string('cpuArchitecture')->nullable();
            $table->string('isMobile')->nullable();
            $table->string('isArmCpu')->nullable();
            $table->string('screenWidth')->nullable();
            $table->string('screenHeight')->nullable();
            $table->string('language')->nullable();
            $table->string('timezone')->nullable();
            $table->string('connectionType')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
