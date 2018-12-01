<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        $users = [
        	['id'=>1, 'name'=>'Jose Mourinho', 'email'=>'mourinho@email.com', 'password'=>bcrypt('manchesterunited')],
        	['id'=>2, 'name'=>'Zlatan Ibrahimovic', 'email'=>'ibrahimovic@email.com', 'password'=>bcrypt('manchesterunited')],
            ['id'=>3, 'name'=>'Wayne rooney', 'email'=>'rooney@email.com', 'password'=>bcrypt('manchesterunited')],
        	['id'=>4, 'name'=>'Ander Herrera', 'email'=>'herrera@email.com', 'password'=>bcrypt('manchesterunited')],
            ['id'=>5, 'name'=>'imsak haqiqy', 'email'=>'imsakhaqiqy24@gmail.com', 'password'=>bcrypt('chelsea')],
        ];

        \DB::table('users')->insert($users);
    }
}
