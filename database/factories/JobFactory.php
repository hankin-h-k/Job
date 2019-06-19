<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function ($faker) {
    return [
        'title'=> $faker->jobTitle,
        'job_time'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'province'=>$faker->state,
        'city'=>$faker->city,
        'dist'=>$faker->secondaryAddress,
        'address'=>$faker->address,
        'lng'=>$faker->longitude(112, 113),
        'lat'=>$faker->latitude(23,30),
        'pay_type'=>array_random(['DAILY', 'MONTHLY']),
        'reward'=>rand(1,1000),
        'need_num'=> random_int(1, 50),
        'joined_num'=>0,
        'intro'=>$faker->text($maxNbChars = 200),
        'linkman'=>$faker->name,
        'link_mobile'=>$faker->phoneNumber,
        'status'=>array_random(['FINISHED', 'UNDERWAY']),
    ];
});
