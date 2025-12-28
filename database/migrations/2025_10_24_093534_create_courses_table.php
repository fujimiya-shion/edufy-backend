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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_center_id')->nullable()->constrained('training_centers')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code', 100)->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            
            // Integer enums
            $table->tinyInteger('level')->unsigned()->default(0)->comment('0=beginner,1=intermediate,2=advanced');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('0=draft,1=published,2=archived');
            
            $table->integer('duration_hours')->unsigned()->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->decimal('tuition_fee', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('cover_image_path', 512)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['training_center_id', 'status', 'level']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
