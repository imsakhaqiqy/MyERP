<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSystemStockAndRealStockNewFromTableProductStockBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stock_balance', function($table){
            $table->float('system_stock',20,2)->after('product_id');
            $table->float('real_stock',20,2)->after('system_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stock_balance', function ($table) {
            $table->dropColumn(['system_stock','real_stock']);
        });
    }
}
