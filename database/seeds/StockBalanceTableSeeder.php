<?php

use Illuminate\Database\Seeder;

class StockBalanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('stock_balances')->delete();
        \DB::table('product_stock_balance')->delete();
    }
}
