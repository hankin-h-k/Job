<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\ApplicationForm;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isJoined($job)
    {
        $count = ApplicationForm::where('user_id', $this->id)->where('job_id', $job->id)->count();
        return $count?true:false;
    }

    public function collectJob($job)
    {
        $collect = JobCollect::where('user_id', $this->id)->where('job_id', $job->id)->first();
        if ($collect) {
            $collect->delect();
        }else{
            $this->jobCollects()->create(['job_id'=>$job->id]);
        }
        return;
    }

    public function forms()
    {
        return $this->hasMany(ApplicationForm::class);
    }

    public function jobCollects()
    {
        return $this->hasMany(JobCollect::class);
    }
}
