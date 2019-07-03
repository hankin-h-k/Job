<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableJobsAddIsRecommend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐')->after('status');
            $table->tinyInteger('is_top')->default(0)->comment('是否置顶')->after('status');
            $table->string('pic')->nullable()->comment('图片')->after('title');
            $table->string('category_id')->nullable()->comment('分类')->after('pic');
            $table->string('wechat')->nullable()->comment('微信号')->after('link_mobile');
            $table->string('link_email')->nullable()->comment('联系email')->after('wechat');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['is_recommend', 'is_top', 'pic', 'category_id','wechat', 'link_email']);
        });
    }
}
