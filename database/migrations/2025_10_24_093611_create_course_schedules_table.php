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
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->tinyInteger('day_of_week')->unsigned()->comment('0=Sun..6=Sat');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('timezone', 64)->default('Asia/Ho_Chi_Minh');
            $table->string('location')->nullable();
            $table->string('room', 120)->nullable();
            $table->date('active_from')->nullable();
            $table->date('active_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('notes')->nullable();
            $table->timestamps();
            
            $table->index(['course_id', 'day_of_week', 'is_active']);
            $table->index(['active_from', 'active_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
