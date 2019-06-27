<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 50)->nullable()->comment('工作名称');
            $table->timestamp('job_time')->nullable()->comment('招聘时间');
            $table->string('province', 20)->nullable()->comment('省份');
            $table->string('city', 20)->nullable()->comment('城市');
            $table->string('dist', 20)->nullable()->comment('区域');
            $table->string('address', 100)->nullable()->commneet('详细地址');
            $table->string('lng', 20)->nullable()->comment('经度');
            $table->string('lat', 20)->nullable()->comment('纬度');
            $table->enum('pay_type', ['DAILY', 'MONTHLY'])->nullable()->comment('结算方式');
            $table->string('reward', 50)->nullable()->comment('报酬');
            $table->tinyInteger('need_num')->default(0)->comment('需要人数');
            $table->tinyInteger('joined_num')->default(0)->comment('报名人数');
            $table->text('intro')->nullable()->comment('介绍');
            $table->string('linkman', 50)->nullable()->comment('联系人');
            $table->string('link_mobile')->nullable()->comment('联系电话');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
