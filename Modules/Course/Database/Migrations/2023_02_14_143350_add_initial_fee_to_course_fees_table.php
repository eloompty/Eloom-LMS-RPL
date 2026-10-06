<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialFeeToCourseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_fees', function (Blueprint $table) {
            $table->float('initial_fee')->default(0)->after('name');
            $table->integer('installment')->default(0)->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_fees', function (Blueprint $table) {
            $table->dropColumn('initial_fee');
            $table->dropColumn('installment');
        });
    }
}
