<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTempSalesReturnHpp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_sales_return_hpp', function(Blueprint $table){
            $table->integer('sales_order_id');
            $table->integer('main_product_id');
            $table->integer('child_product_id');
            $table->integer('amount_return_per_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('temp_sales_return_hpp');
    }
}
