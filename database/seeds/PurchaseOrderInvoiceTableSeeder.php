<?php

use Illuminate\Database\Seeder;

class PurchaseOrderInvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_order_invoices')->delete();
    }
}
