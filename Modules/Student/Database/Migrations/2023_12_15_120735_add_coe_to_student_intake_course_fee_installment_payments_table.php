<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoeToStudentIntakeCourseFeeInstallmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_intake_course_fee_installment_payments', function (Blueprint $table) {
            $table->string('enrollment_fee_agent')->nullable()->after('total_amount');
            $table->string('material_fee_agent')->nullable()->after('enrollment_fee_agent');
            $table->integer('gst_percent')->default(10)->after('gst');
            $table->float('coe')->nullable()->after('payment_type');
            $table->float('oshc')->nullable()->after('coe');
            $table->float('accomodation_placement')->nullable()->after('oshc');
            $table->float('airport_pickup')->nullable()->after('accomodation_placement');
            $table->string('other_fee_title')->nullable()->after('airport_pickup');
            $table->float('other_fee')->nullable()->after('other_fee_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_intake_course_fee_installment_payments', function (Blueprint $table) {
            $table->dropColumn('enrollment_fee_agent');
            $table->dropColumn('material_fee_agent');
            $table->dropColumn('gst_percent');
            $table->dropColumn('coe');
            $table->dropColumn('oshc');
            $table->dropColumn('accomodation_placement');
            $table->dropColumn('airport_pickup');
            $table->dropColumn('other_fee_title');
            $table->dropColumn('other_fee');
        });
    }
}
