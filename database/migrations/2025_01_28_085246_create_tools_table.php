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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' );
            $table->longtext( 'description' )->nullable();
            $table->string( 'logo' )->nullable();
            $table->integer( 'price' )->default(0);
            $table->string( 'link' )->nullable();
            $table->float( 'avg_rating' )->default(0);
            $table->integer( 'total_reviews' )->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
