<?php

use Illuminate\Database\Seeder;

class InvoceTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoice_terms')->delete();
        $data = [
        	['id'=>1, 'name'=>'1 Minggu', 'day_many'=>7],
        	['id'=>2, 'name'=>'2 Minggu', 'day_many'=>14],
        ];
        \DB::table('invoice_terms')->insert($data);
    }
}
