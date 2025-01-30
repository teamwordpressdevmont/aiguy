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
        Schema::create('bookmark_folder_tool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('bookmark_folders')->onDelete('cascade');
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_folder_tool');
    }
};
