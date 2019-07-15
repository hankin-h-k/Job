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

    public function top()
    {
    	if ($this->is_top) {//取消置顶
    		//当前置顶以上的置顶状态-1
            Job::where('is_top', '>', $this->is_top)->decrement('is_top', 1);
            $this->is_top = 0;
            $this->save();
    	}else{//置顶
	    	$top_count = Job::where('is_top', '>', 0)->count();
	    	$top_count = $top_count?$top_count:0;
	    	$this->is_top = $top_count +1;
	    	$this->save();
    	}
    	return;
    }

    public function category()
    {
        return $this->hasOne(JobCategory::class, 'id', 'category_id');
    }
}
