<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $fillable = [
        'title',
        'description',
        'salary',
        'location',
        'department',
        'job_type',
        'category_id',
        'status',
    ];

    protected $casts = [
        'department' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(JobCategory::class);
    }
}
