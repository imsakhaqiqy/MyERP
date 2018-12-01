<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_methods')->delete();
        $data = [
            ['id'=>1, 'code'=>'BTR', 'name'=>'Bank Transfer'],
            ['id'=>2, 'code'=>'CSH', 'name'=>'Cash'],
        	['id'=>3, 'code'=>'GIR', 'name'=>'GIRO'],
        ];
        \DB::table('payment_methods')->insert($data);
    }
}
