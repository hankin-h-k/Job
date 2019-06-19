<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 20)->nullable()->after('name');
            $table->tinyInteger('sex')->default(1)->after('mobile');
            $table->tinyInteger('age')->default(0)->after('sex');
            $table->string('ducation', 10)->nullable()->after('age');
            $table->string('school', 50)->nullable()->after('ducation');
            $table->string('province', 20)->nullable()->after('school');
            $table->string('city',20)->nullable()->after('province');
            $table->string('dist', 20)->nullable()->after('city');
            $table->string('job_type', 20)->nullable()->after('dist');
            $table->enum('pay_type', ['DAILY', 'MONTHLY'])->nullable()->after('job_type');

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
            $table->dropcolumn(['mobile', 'sex', 'age', 'ducation', 'school', 'province', 'city', 'dist', 'job_type', 'pay_type']);
        });
    }
}
