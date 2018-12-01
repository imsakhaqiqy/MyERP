<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('purchase_order_id');
            $table->decimal('bill_price', 20, 2);
            $table->decimal('paid_price', 20, 2);
            $table->datetime('paid_at')->nullable();
            $table->enum('status',['completed', 'uncompleted'])->default('uncompleted');
            $table->integer('created_by');
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
        Schema::drop('purchase_order_invoices');
    }
}
