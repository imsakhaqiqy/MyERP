<?php

use Illuminate\Database\Seeder;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('families')->delete();
        $data = [
        	['id'=>1, 'code'=>'FAM1', 'name'=>'Family 1'],
        	['id'=>2, 'code'=>'FAM2', 'name'=>'Family 2'],
        ];
        \DB::table('families')->insert($data);
    }
}
