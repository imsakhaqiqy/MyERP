<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStockBalanceProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_balance', function(Blueprint $table){
            $table->integer('stock_balance_id');
            $table->integer('product_id');
            $table->integer('system_stock');
            $table->integer('real_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_stock_balance');
    }
}
