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
            $table->string('title', 255)->unique();
            $table->string('author', 150)->nullable();
            $table->text('description');
            $table->longText('content');
            $table->string('url', 2083); 
            $table->string('url_to_image', 2083); 
            $table->string('source_name');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->timestamp('published_at');
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
