<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('category_course_relation', function (Blueprint $table) {
            // Ensure columns have the correct type (if needed)
            $table->unsignedBigInteger('course_id')->change();
            $table->unsignedBigInteger('category_id')->change();

            // Add foreign key constraints
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category_courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('category_course_relation', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['course_id']);
            $table->dropForeign(['category_id']);
        });
    }
};