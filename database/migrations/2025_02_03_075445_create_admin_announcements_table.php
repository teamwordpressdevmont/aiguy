<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('announcement_name');
            $table->text('announcement_description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_announcements');
    }
};