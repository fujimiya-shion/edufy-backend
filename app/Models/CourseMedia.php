<?php

namespace App\Models;

use App\Enums\MediaStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseMedia extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'disk',
        'original_path',
        'original_mime',
        'original_size',
        'duration_seconds',
        'playback_manifest_path',
        'renditions',
        'thumbnails',
        'status',
        'processing_job_id',
        'failure_reason',
        'sort_order',
        'meta',
    ];

    protected $casts = [
        'status' => MediaStatus::class,
        'renditions' => 'array',
        'thumbnails' => 'array',
        'meta' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonMedia(): HasMany
    {
        return $this->hasMany(LessonMedia::class);
    }
}
