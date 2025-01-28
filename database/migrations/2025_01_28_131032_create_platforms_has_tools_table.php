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
        Schema::create('platforms_has_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained('platforms');
            $table->foreignId('tool_id')->constrained('tools');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms_has_tools');
    }
};
