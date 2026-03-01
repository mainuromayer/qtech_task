<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{

    protected $table = 'job_categories';
    protected $fillable = [
        'title',
        'icon',
        'sort_order',
        'status',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class , 'category_id');
    }
}
