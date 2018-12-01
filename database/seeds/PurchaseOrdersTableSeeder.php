<?php

use Illuminate\Database\Seeder;

class PurchaseOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_orders')->delete();
        //also delete pivot table with product
        \DB::table('product_purchase_order')->delete();
        $data = [
        	['id'=>1, 'code'=>'PO-1', 'supplier_id'=>1, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        	['id'=>2, 'code'=>'PO-2', 'supplier_id'=>1, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        	['id'=>3, 'code'=>'PO-3', 'supplier_id'=>2, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        ];

        \DB::table('purchase_orders')->insert($data);

        //delete purchase_order_invoices table datas
        \DB::table('purchase_order_invoices')->delete();
        //delete purchase_invoice_payments table
        \DB::table('purchase_invoice_payments')->delete();
    }
}
