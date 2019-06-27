<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [];
    protected $guarded = [];

    public function forms()
    {
    	return $this->hasMany(ApplicationForm::class);
    }
}
