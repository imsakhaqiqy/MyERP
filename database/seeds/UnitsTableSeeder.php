<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('units')->delete();
        $data = [
        	['id'=>1, 'name'=>'100 ml bootle'],
        	['id'=>2, 'name'=>'150 ml bootle'],
        	['id'=>3, 'name'=>'200 ml bootle'],
        	['id'=>4, 'name'=>'1 kg pack'],
        	['id'=>5, 'name'=>'2 kg pack'],
        ];

        \DB::table('units')->insert($data);
    }
}
