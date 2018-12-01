<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('sales_order_id');
            $table->decimal('bill_price', 20, 2);
            $table->decimal('paid_price', 20, 2);
            $table->datetime('paid_at')->nullable();
            $table->enum('status',['completed', 'uncompleted'])->default('uncompleted');
            $table->integer('created_by');
            $table->text('notes')->nullable();
            $table->date('due_date');
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
        Schema::drop('sales_order_invoices');
    }
}
