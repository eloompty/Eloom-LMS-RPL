<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('school_based_flag')->nullable();
            $table->string('school_level_identifier')->nullable();
            $table->string('school_type_identifier')->nullable();
            $table->string('specific_funding_identifier')->nullable();
            $table->string('statistical_area_level_1_identifier')->default('@@@@@@@@@@@');
            $table->string('statistical_area_level_2_identifier')->default('@@@@@@@@@');
            $table->string('high_school_level_completed_identifier')->default('@@');
            $table->string('hours_attended')->nullable();
            $table->string('indigenous_status_identifier')->default('@');
            $table->string('language_identifier')->default('@@@@');
            $table->string('unique_student_identifier')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('email_alternative')->nullable();
            $table->string('gender')->nullable();
            $table->string('disability_flag')->default('@');
            $table->string('disability_identifier')->nullable();
            $table->string('survey_contact_status')->default('A');
            $table->string('prior_education')->default('@');
            $table->string('prior_education_achievement_identifier')->nullable();
            $table->string('funding_source_national')->default('32');
            $table->string('funding_source_state_training_authority')->nullable();
            $table->string('at_school')->default('@');
            $table->string('anzsco')->nullable();
            $table->string('anzsic')->default('@@@@');
            $table->string('student_id_national')->nullable();
            $table->string('student_id_apprenticeships')->default('@@@@@@@@@@');
            $table->string('study_reason')->nullable();
            $table->string('labour_force_status_identifier')->default('@@');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('school_based_flag');
            $table->dropColumn('school_level_identifier');
            $table->dropColumn('school_type_identifier');
            $table->dropColumn('specific_funding_identifier');
            $table->dropColumn('statistical_area_level_1_identifier');
            $table->dropColumn('statistical_area_level_2_identifier');
            $table->dropColumn('high_school_level_completed_identifier');
            $table->dropColumn('hours_attended');
            $table->dropColumn('indigenous_status_identifier');
            $table->dropColumn('language_identifier');
            $table->dropColumn('unique_student_identifier');
            $table->dropColumn('phone_work');
            $table->dropColumn('email_alternative');
            $table->dropColumn('gender');
            $table->dropColumn('disability_flag');
            $table->dropColumn('disability_identifier');
            $table->dropColumn('survey_contact_status');
            $table->dropColumn('prior_education');
            $table->dropColumn('prior_education_achievement_identifier');
            $table->dropColumn('funding_source_national');
            $table->dropColumn('funding_source_state_training_authority');
            $table->dropColumn('at_school');
            $table->dropColumn('anzsco');
            $table->dropColumn('anzsic');
            $table->dropColumn('student_id_national');
            $table->dropColumn('student_id_apprenticeships');
            $table->dropColumn('study_reason');
            $table->dropColumn('labour_force_status_identifier');
        });
    }
}
