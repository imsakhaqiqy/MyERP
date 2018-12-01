<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSaldoAwalFromSubChartAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_chart_accounts', function($table){
            $table->decimal('saldo_awal',20,2)->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_chart_accounts', function($table){
            $table->dropColumn('saldo_awal');
        });
    }
}
