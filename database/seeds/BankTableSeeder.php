<?php

use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('banks')->delete();
        
        $data = [
        	['id'=>1, 'code'=>'BNK-01', 'name'=>'Bank BRI', 'account_name'=>'PT Saya', 'account_number'=>'00123456', 'value'=>0],
        	['id'=>2, 'code'=>'BNK-02', 'name'=>'Bank BNI', 'account_name'=>'PT Saya', 'account_number'=>'65432100', 'value'=>0]
        ];
        
        \DB::table('banks')->insert($data);
    }
}
