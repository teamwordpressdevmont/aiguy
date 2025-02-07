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
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('left_image')->nullable()->before('created_at');
            $table->text('right_text')->nullable()->before('created_at');
            $table->text('middle_text')->nullable()->before('created_at');
            $table->string('middle_image')->nullable()->before('created_at');
            $table->string('sub_title')->nullable()->before('created_at');
            $table->text('sub_content')->nullable()->before('created_at');
            $table->string('sub_image')->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'left_image',
                'right_text',
                'middle_text',
                'middle_image',
                'sub_title',
                'sub_content',
                'sub_image'
            ]);
        });
    }
};
