<?php

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('suppliers')->delete();
        $data = [
        	['id'=>1, 'code'=>'SUP-001', 'name'=>'Best Buy', 'pic_name'=>'Paul Frank', 'primary_email'=>'john@bestbuy.com'],
        	['id'=>2, 'code'=>'SUP-002', 'name'=>'Great Buy', 'pic_name'=>'Leena Shawn', 'primary_email'=>'leena@greatbuy.com'],
        ];
        \DB::table('suppliers')->insert($data);
    }
}
