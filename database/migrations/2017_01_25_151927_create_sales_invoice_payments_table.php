<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_payments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_invoice_id');
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
        Schema::drop('sales_invoice_payments');
    }
}
