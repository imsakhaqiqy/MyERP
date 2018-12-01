<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBankPurchaseInvoicePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_purchase_invoice_payment', function(Blueprint $table){
            $table->increments('id');
            $table->integer('bank_id');
            $table->integer('purchase_invoice_payment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bank_purchase_invoice_payment');
    }
}
