<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReferenceAtTableTransactionChartAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_chart_accounts',function(Blueprint $table){
            $table->integer('reference');
            $table->string('source');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_chart_accounts',function(Blueprint $table){
            $table->dropColumn(['reference','source','type']);
        });
    }
}
