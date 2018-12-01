<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnPaymentMethodFromTablePurchaseOrderInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('purchase_order_invoices', 'payment_method_id')){
            Schema::table('purchase_order_invoices', function(Blueprint $table){
                $table->dropColumn('payment_method_id');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('purchase_order_invoices');
    }
}
