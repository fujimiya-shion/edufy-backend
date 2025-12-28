<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ribbon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'order',
    ];

    public function items()
    {
        return $this->hasMany(RibbonItem::class);
    }

    public function activeItems()
    {
        return $this->items()->with('course')->orderBy('order');
    }
}
