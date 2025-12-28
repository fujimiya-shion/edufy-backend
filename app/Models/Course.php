<?php

namespace App\Models;

use App\Enums\CourseLevel;
use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_center_id',
        'title',
        'slug',
        'code',
        'short_description',
        'description',
        'level',
        'status',
        'duration_hours',
        'capacity',
        'tuition_fee',
        'start_date',
        'end_date',
        'cover_image_path',
        'meta',
    ];

    protected $casts = [
        'level' => CourseLevel::class,
        'status' => CourseStatus::class,
        'tuition_fee' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'meta' => 'array',
    ];

    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher')
            ->withPivot('role', 'sort_order')
            ->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(CourseMedia::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
