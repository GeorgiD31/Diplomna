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
        Schema::create('articles', function (Blueprint $table) {            
            $table->id();
            $table->string('title')->unique();
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url', 2083)->nullable(); 
            $table->string('url_to_image', 2083)->nullable(); 
            $table->string('source_name')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
