<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes, Slugable;

    protected $fillable = [
        'training_center_id',
        'full_name',
        'slug',
        'email',
        'phone',
        'title',
        'bio',
        'avatar_path',
        'is_active',
        'skills',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'skills' => 'array',
    ];

    protected $slugable = ['full_name'];

    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teacher')
            ->withPivot('role', 'sort_order')
            ->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }
}
