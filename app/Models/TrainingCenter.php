<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingCenter extends Model
{
    use HasFactory, SoftDeletes, Slugable;

    protected $slugable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'code',
        'email',
        'phone',
        'website',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'timezone',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
