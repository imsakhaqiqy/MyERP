<?php

use Illuminate\Database\Seeder;

class PurchaseReturnTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_returns')->delete();
    }
}
