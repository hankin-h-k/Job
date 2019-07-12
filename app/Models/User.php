<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        $count = $this->forms()->where('job_id', $job->id)->count();
        return $count?1:0;
    }

    public function collectJob($job)
    {
        $collect = JobCollect::where('user_id', $this->id)->where('job_id', $job->id)->first();
        if ($collect) {
            $collect->delete();
        }else{
            $this->jobCollects()->create(['job_id'=>$job->id]);
        }
        return;
    }

    public function getValidFormId()
    {
        $seven_day_age = date('Y-m-d H:i:s', strtotime('-7 day'));
        $form = $this->formIds()->where('status', 0)->where('created_at', '>', $seven_day_age)->orderBy('id', 'asc')->first();
        return $form;
    }

    public function forms()
    {
        return $this->hasMany(ApplicationForm::class);
    }

    public function jobCollects()
    {
        return $this->hasMany(JobCollect::class);
    }

    public function formIds()
    {
        return $this->hasMany(FormId::class);
    }

    public function wechat()
    {
        return $this->hasOne(Wechat::class);
    }
    public function collects()
    {
        return $this->hasMany(JobCollect::class);
    }
}
