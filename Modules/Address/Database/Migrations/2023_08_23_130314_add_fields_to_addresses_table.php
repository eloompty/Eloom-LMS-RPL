<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('flat_unit')->after('building_number')->nullable();
            $table->string('street_no')->after('flat_unit')->default(0);
            $table->string('p_o_box')->after('street_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('flat_unit');
            $table->dropColumn('street_no');
            $table->dropColumn('p_o_box');
        });
    }
}
