<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentIntakeCourseCompetenceCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_intake_course_competence_certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_intake_course_competence_id');
            $table->bigInteger('certificate_template_id');
            $table->string('certificate_type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('student_intake_course_competence_certificates');
    }
}
