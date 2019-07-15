<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCollect extends Model
{
    protected $fillable = [];
    protected $guarded = [];

    public function job()
    {
    	return $this->hasOne(Job::class, 'id', 'job_id');
    }
}
