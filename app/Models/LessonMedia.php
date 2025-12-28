<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'course_media_id',
        'title',
        'sort_order',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function courseMedia(): BelongsTo
    {
        return $this->belongsTo(CourseMedia::class);
    }
}
