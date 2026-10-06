<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseFeeInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_fee_installments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_fee_id');
            $table->string('name');
            $table->float('enrollment_fee')->nullable();
            $table->float('material_fee')->nullable();
            $table->float('amount');
            $table->date('due_date');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('student_intake_course_fee_installments');
    }
}
