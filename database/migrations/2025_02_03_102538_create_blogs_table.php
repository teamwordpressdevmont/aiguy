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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete( 'cascade' );
            $table->string('featured_image')->nullable();
            $table->string('heading');
            $table->string('reading_time');
            $table->longText('content');
            $table->string('left_image')->nullable();
            $table->longText('right_text');
            $table->longText('middle_text');
            $table->string('middle_image')->nullable();
            $table->string('sub_title');
            $table->longText('sub_content');
            $table->string('sub_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
