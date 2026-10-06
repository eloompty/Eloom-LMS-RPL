<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('duration')->unsigned()->nullable()->change();
            $table->integer('study_period')->unsigned()->nullable()->change();
            $table->integer('study_break')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('duration')->unsigned()->nullable(false)->change();
            $table->integer('study_period')->unsigned()->nullable(false)->change();
            $table->integer('study_break')->unsigned()->nullable(false)->change();
        });
    }
}
