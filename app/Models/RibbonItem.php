<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RibbonItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ribbon_id',
        'course_id',
        'order',
    ];

    public function ribbon()
    {
        return $this->belongsTo(Ribbon::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
