<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialFeeToStudentIntakeCourseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_intake_course_fees', function (Blueprint $table) {
            $table->float('initial_fee')->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_intake_course_fees', function (Blueprint $table) {
            $table->dropColumn('initial_fee');
        });
    }
}
