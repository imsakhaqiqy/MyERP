<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnNewAmountFromTableTransactionChartAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_chart_accounts', function($table){
            $table->decimal('amount',20,2)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_chart_accounts', function ($table) {
            $table->dropColumn('amount');
        });
    }
}
