<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnAmountReturnPerUnitFromTempPurchaseReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_purchase_return', function($table){
            $table->decimal('amount_return_per_unit',20,2)->after('child_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_purchase_return', function ($table) {
            $table->dropColumn('amount_return_per_unit');
        });
    }
}
