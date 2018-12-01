<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table){
            $table->increments('id');
            $table->integer('sales_order_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->text('notes');
            $table->enum('status',['posted', 'accept', 'resent'])->default('posted');
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
        Schema::drop('sales_returns');
    }
}
