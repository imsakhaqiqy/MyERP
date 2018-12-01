<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductaSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales_order', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('sales_order_id');
            $table->integer('quantity');
            $table->decimal('price', 20, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_sales_order');
    }
}
