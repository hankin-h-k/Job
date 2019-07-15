<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsersAddAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->comment('地址')->after('dist');
            $table->integer('category_id')->default(0)->after('address');
            $table->tinyInteger('is_completed')->default(0)->after('category_id');
            $table->tinyInteger('is_admin')->default(0)->after("is_completed");
            $table->string('avatar')->nullable()->comment('头像')->after('name');
            $table->tinyInteger('is_shielded')->default(0)->comment('是否屏蔽')->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'category_id', 'is_completed', 'is_admin', 'avatar','is_shielded']);
        });
    }
}
