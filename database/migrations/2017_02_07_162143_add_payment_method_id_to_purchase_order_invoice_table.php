<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodIdToPurchaseOrderInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_invoices', function($table){
            $table->integer('payment_method_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('purchase_order_invoices', 'payment_method_id')){
            Schema::table('purchase_order_invoices', function($table){
                $table->dropColumn('payment_method_id');
            });    
        }
        
    }
}
