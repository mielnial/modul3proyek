<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    public const STATUSES = ['Planned', 'Ongoing', 'Done'];

    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }
}