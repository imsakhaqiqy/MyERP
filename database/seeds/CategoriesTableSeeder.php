<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->delete();
        $data = [
        	['id'=>1, 'code'=>'CAT1', 'name'=>'Category 1'],
        	['id'=>2, 'code'=>'CAT2', 'name'=>'Category 2'],
        ];

        \DB::table('categories')->insert($data);
    }
}
