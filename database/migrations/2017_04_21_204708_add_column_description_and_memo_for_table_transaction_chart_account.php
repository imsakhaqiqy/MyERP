<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescriptionAndMemoForTableTransactionChartAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_chart_accounts', function($table){
            $table->string('description')->after('type');
            $table->string('memo')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_chart_accounts', function($table){
            $table->dropColumn(['description','memo']);
        });
    }
}
