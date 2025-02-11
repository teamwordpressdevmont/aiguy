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
            Schema::rename('category_course', 'category_course_relation');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('category_course_relation', 'category_course');

    }
};
