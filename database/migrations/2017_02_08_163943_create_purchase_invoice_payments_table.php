<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_invoice_id');
            $table->decimal('amount', 20, 2);
            $table->integer('payment_method_id');
            $table->integer('receiver')->nullable();
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
        Schema::drop('purchase_invoice_payments');
    }
}
