<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDriverIdAndVehicleIdFromTableSalesOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders',function($table){
            $table->integer('driver_id')->after('status');
            $table->integer('vehicle_id')->after('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_orders',function($table){
            $table->dropColumn(['driver_id','vehicle_id']);
        });
    }
}
