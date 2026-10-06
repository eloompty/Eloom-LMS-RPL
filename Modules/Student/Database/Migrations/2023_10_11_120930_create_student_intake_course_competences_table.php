<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseCompetencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_competences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_id');
            $table->string('award_status')->nullable();
            $table->string('certificate_type')->nullable();
            $table->date('parchment_issue_date')->nullable();
            $table->string('parchment_no')->nullable();
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
        Schema::dropIfExists('student_intake_course_competences');
    }
}
