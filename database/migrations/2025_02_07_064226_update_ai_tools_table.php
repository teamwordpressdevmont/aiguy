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
        Schema::table('ai_tools', function (Blueprint $table) {
            $table->string('short_description_heading')->nullable()->change();
            $table->text('short_description')->nullable()->change();
            $table->string('website_link')->nullable()->change();
            $table->string('description_heading')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->longText('key_features')->nullable()->change();
            $table->longText('pros')->nullable()->change();
            $table->longText('cons')->nullable()->change();
            $table->longText('long_description')->nullable()->change();
            $table->string('aitool_filter')->nullable()->change();
            $table->integer('added_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_tools', function (Blueprint $table) {
            $table->string('short_description_heading')->nullable(false)->change();
            $table->text('short_description')->nullable(false)->change();
            $table->string('website_link')->nullable(false)->change();
            $table->string('description_heading')->nullable(false)->change();
            $table->longText('description')->nullable(false)->change();
            $table->longText('key_features')->nullable(false)->change();
            $table->longText('pros')->nullable(false)->change();
            $table->longText('cons')->nullable(false)->change();
            $table->longText('long_description')->nullable(false)->change();
            $table->string('aitool_filter')->nullable(false)->change();
            $table->integer('added_by')->nullable(false)->change();
        });
    }
};
