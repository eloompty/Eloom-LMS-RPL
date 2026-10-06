<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFundingToStudentIntakeUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_intake_units', function (Blueprint $table) {
            $table->string('funding_source_national')->default('32')->after('intake_unit_id');
            $table->string('funding_source_state_training_authority')->nullable()->after('funding_source_national');
            $table->string('delivery_mode')->nullable()->after('funding_source_state_training_authority');
            $table->string('internal')->nullable()->after('delivery_mode');
            $table->string('predominant_delivery_mode')->nullable()->after('internal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_intake_units', function (Blueprint $table) {
            $table->dropColumn('funding_source_national');
            $table->dropColumn('funding_source_state_training_authority');
            $table->dropColumn('delivery_mode');
            $table->dropColumn('internal');
            $table->dropColumn('predominant_delivery_mode');
        });
    }
}
