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
        Schema::create('ai_tools', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('slug')->unique();
            $table->string('name')->unique(); // Unique Name
            $table->string('logo')->nullable(); // Logo URL or Path
            $table->string('cover')->nullable(); // Image URL or Path
            $table->string('tagline')->nullable();
            $table->string('short_description_heading'); // Short Description Heading
            $table->text('short_description'); // Short Description
            $table->boolean('verified_status')->default(0); // 0 = Not Verified, 1 = Verified
            $table->string('payment_status', 100, ['Free', 'Paid'])->default('Free'); // Payment Status
            $table->string('payment_text')->nullable(); // Additional Payment Info
            $table->string('website_link'); // Website Link
            $table->string('description_heading'); // Description Heading
            $table->longText('description'); // Description
            $table->longText('key_features'); // Key Features
            $table->longText('pros'); // Pros
            $table->longText('cons'); // Cons
            $table->longText('long_description')->nullable(); // Long Description
            $table->string('aitool_filter')->nullable(); // Long Description
            $table->integer('added_by')->nullable(); // Long Description
            $table->timestamps(); // Created & Updated Timestamps
        });
    }
 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_tools');
    }
};
