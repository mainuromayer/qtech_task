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
        'resume',
        'cover_letter',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
