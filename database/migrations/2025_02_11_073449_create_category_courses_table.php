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
        if (!Schema::hasTable('category_courses')) {
            Schema::create('category_courses', function (Blueprint $table) {
                $table->id();
                $table->string('name'); 
                $table->string('icon'); 
                $table->string('description')->nullable(); 
                $table->string('slug'); 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_courses');
    }
};
