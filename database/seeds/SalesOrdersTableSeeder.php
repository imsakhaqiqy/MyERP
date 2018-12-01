<?php

use Illuminate\Database\Seeder;

class SalesOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sales_orders')->delete();
        //also delete pivot table with product
        //\DB::table('product_sales_order')->delete();
        $data = [
        	['id'=>1, 'code'=>'SO-1', 'customer_id'=>1, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        	['id'=>2, 'code'=>'SO-2', 'customer_id'=>1, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        	['id'=>3, 'code'=>'SO-3', 'customer_id'=>2, 'creator'=>1, 'status'=>'posted', 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')],
        ];

        \DB::table('sales_orders')->insert($data);

        //delete sales_order_invoices table datas
        \DB::table('sales_order_invoices')->delete();
        //delete sales_invoice_payments table
        \DB::table('sales_invoice_payments')->delete();
    }
}
