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
        Schema::create('site_infos', function (Blueprint $table) {
            $table->id();
            $table->string('siteFavicon')->nullable();
            $table->string('siteLogo')->nullable();
            $table->integer('sitePostsPerPage')->default(10)->nullable();
            $table->string('siteName')->default('blogName')->nullable();
            $table->string('siteDescription')->default('Lorem ipsum dolor sit amet consectetur adipisicing elit.')->nullable();
            $table->string('siteUrl')->nullable();
            $table->string('siteAdminEmail')->nullable();
            $table->string('siteLanguage')->default('en');
            $table->enum('siteStatus', ['active', 'maintenance', 'closed'])->default('active');
            $table->enum('siteLogoOptions', ['logo', 'logo_title', 'logo_title_description', 'title_description'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_infos');
    }
};
