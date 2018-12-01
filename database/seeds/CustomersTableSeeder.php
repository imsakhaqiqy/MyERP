<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('customers')->delete();
        $data = [
        	['id'=>1, 'code'=>'CST-1', 'name'=>'Customer 1', 'address'=>'CS1 street', 'phone_number'=>'021-686868', 'invoice_term_id'=>1],
        	['id'=>2, 'code'=>'CST-2', 'name'=>'Customer 2', 'address'=>'CS2 street', 'phone_number'=>'021-6767686','invoice_term_id'=>2],
        ];

        \DB::table('customers')->insert($data);
    }
}
