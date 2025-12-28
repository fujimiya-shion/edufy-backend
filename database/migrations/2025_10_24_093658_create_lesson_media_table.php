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
        Schema::create('lesson_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->uuid('course_media_id')->nullable()->constrained('course_media')->onDelete('set null');
            $table->string('title')->nullable();
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamps();
            
            $table->unique(['lesson_id', 'course_media_id']);
            $table->index(['lesson_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_media');
    }
};
