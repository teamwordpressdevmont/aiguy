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
        Schema::create('tool_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->onDelete( 'cascade' );
            $table->integer('user_id');
            $table->foreignId('parent_comment_id')->constrained('tool_comments')->onDelete( 'cascade' )->nullable();
            $table->string( 'comment' );
            $table->integer( 'status' )->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_comments');
    }
};
