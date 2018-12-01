<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnPricePerUnitFromTempSalesInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_sales_invoice', function($table){
            $table->decimal('price_per_unit',20,2)->after('child_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_sales_invoice', function ($table) {
            $table->dropColumn('price_per_unit');
        });
    }
}
