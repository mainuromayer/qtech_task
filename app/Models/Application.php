<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';
    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'resume_link',
        'cover_note',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
