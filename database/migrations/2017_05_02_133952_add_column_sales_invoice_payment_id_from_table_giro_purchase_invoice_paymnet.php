<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSalesInvoicePaymentIdFromTableGiroPurchaseInvoicePaymnet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('giro_sales_invoice_payment', function($table){
        $table->integer('sales_invoice_payment_id')->after('amount');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
