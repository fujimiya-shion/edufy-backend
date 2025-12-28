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
        Schema::create('course_media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('disk', 50)->default('public');
            $table->string('original_path', 1024);
            $table->string('original_mime', 127)->nullable();
            $table->bigInteger('original_size')->unsigned()->nullable();
            $table->integer('duration_seconds')->unsigned()->nullable();
            $table->string('playback_manifest_path', 1024)->nullable();
            $table->json('renditions')->nullable();
            $table->json('thumbnails')->nullable();
            
            $table->tinyInteger('status')->unsigned()->default(0)->comment('0=uploaded,1=processing,2=ready,3=failed');
            $table->string('processing_job_id', 191)->nullable();
            $table->text('failure_reason')->nullable();
            
            $table->integer('sort_order')->unsigned()->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            
            $table->index(['course_id', 'sort_order']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_media');
    }
};
