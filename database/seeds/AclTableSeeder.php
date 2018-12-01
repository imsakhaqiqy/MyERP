<?php

use Illuminate\Database\Seeder;

class AclTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Block table roles
	    DB::table('roles')->delete();
        $roles = [
        	['id'=>1, 'code'=>'SUP', 'name'=>'Super Admin', 'label'=>'User with this role will have full access to apllication'],
        	['id'=>2, 'code'=>'ADM', 'name'=>'Administrator', 'label'=>'User with this role will have semi-full access to apllication'],
        	['id'=>3, 'code'=>'FIN', 'name'=>'Finance', 'label'=>'User with this role will have full access to finance'],
        	['id'=>4, 'code'=>'WRH', 'name'=>'Warehouse', 'label'=>'User with this role will have full access to warehouse'],
          ['id'=>5, 'code'=>'MKT', 'name'=>'Marketing', 'label'=>'User with this role will have full access to marketing'],
        ];
        DB::table('roles')->insert($roles);
	    //ENDBlock table roles

        //Block table role_user
	    DB::table('role_user')->delete();
        $role_user = [
        	['role_id'=>1, 'user_id'=>1],
        	['role_id'=>2, 'user_id'=>2],
          ['role_id'=>3, 'user_id'=>3],
        	['role_id'=>4, 'user_id'=>4],
          ['role_id'=>2, 'user_id'=>5],
        ];
        DB::table('role_user')->insert($role_user);
        //ENDBlock table role_user

        //Block table permissions
        DB::table('permissions')->delete();
        $permissions = [
                # Purchase Order
                ['id'=>1, 'slug'=>'purchase-order-module', 'description'=>''],
                ['id'=>2, 'slug'=>'create-purchase-order-module', 'description'=>''],
                ['id'=>3, 'slug'=>'edit-purchase-order-module', 'description'=>''],
                ['id'=>4, 'slug'=>'delete-purchase-order-module', 'description'=>''],
                # Purchase Order Invoice
                ['id'=>5, 'slug'=>'purchase-order-invoice-module', 'description'=>''],
                ['id'=>6, 'slug'=>'create-purchase-order-invoice-module', 'description'=>''],
                ['id'=>7, 'slug'=>'edit-purchase-order-invoice-module', 'description'=>''],
                ['id'=>8, 'slug'=>'delete-purchase-order-invoice-module', 'description'=>''],
                ['id'=>9, 'slug'=>'create-purchase-order-invoice-payment-module', 'description'=>''],
                # Purchase Order Return
                ['id'=>10, 'slug'=>'purchase-order-return-module', 'description'=>''],
                ['id'=>11, 'slug'=>'create-purchase-order-return-module', 'description'=>''],
                ['id'=>12, 'slug'=>'edit-purchase-order-return-module', 'description'=>''],
                ['id'=>13, 'slug'=>'delete-purchase-order-return-module', 'description'=>''],
                # Sales Order
                ['id'=>14, 'slug'=>'sales-order-module', 'description'=>''],
                ['id'=>15, 'slug'=>'create-sales-order-module', 'description'=>''],
                ['id'=>16, 'slug'=>'edit-sales-order-module', 'description'=>''],
                ['id'=>17, 'slug'=>'delete-sales-order-module', 'description'=>''],
                # Sales Order Invoice
                ['id'=>18, 'slug'=>'sales-order-invoice-module', 'description'=>''],
                ['id'=>19, 'slug'=>'create-sales-order-invoice-module', 'description'=>''],
                ['id'=>20, 'slug'=>'edit-sales-order-invoice-module', 'description'=>''],
                ['id'=>21, 'slug'=>'delete-sales-order-invoice-module', 'description'=>''],
                ['id'=>22, 'slug'=>'create-sales-order-invoice-payment-module', 'description'=>''],
                # Sales Order Return
                ['id'=>23, 'slug'=>'sales-order-return-module', 'description'=>''],
                ['id'=>24, 'slug'=>'create-sales-order-return-module', 'description'=>''],
                ['id'=>25, 'slug'=>'edit-sales-order-return-module', 'description'=>''],
                ['id'=>26, 'slug'=>'delete-sales-order-return-module', 'description'=>''],

                //Inventory
                #Product Adjustment
                ['id'=>27, 'slug'=>'product-adjustment-module', 'description'=>''],
                ['id'=>28, 'slug'=>'create-product-adjustment-module', 'description'=>''],
                ['id'=>29, 'slug'=>'edit-product-adjustment-module', 'description'=>''],
                ['id'=>30, 'slug'=>'delete-product-adjustment-module', 'description'=>''],
                #List Product Available
                ['id'=>31, 'slug'=>'product-available', 'description'=>''],
                #List Product (All)
                ['id'=>32, 'slug'=>'product-all', 'description'=>''],
                #Main Product
                ['id'=>33, 'slug'=>'product-module', 'description'=>''],
                ['id'=>34, 'slug'=>'create-product-module', 'description'=>''],
                ['id'=>35, 'slug'=>'edit-product-module', 'description'=>''],
                ['id'=>36, 'slug'=>'delete-product-module', 'description'=>''],
                #Product Family
                ['id'=>37, 'slug'=>'family-module', 'description'=>''],
                ['id'=>38, 'slug'=>'create-family-module', 'description'=>''],
                ['id'=>39, 'slug'=>'edit-family-module', 'description'=>''],
                ['id'=>40, 'slug'=>'delete-family-module', 'description'=>''],
                #Product Category
                ['id'=>41, 'slug'=>'category-module', 'description'=>''],
                ['id'=>42, 'slug'=>'create-category-module', 'description'=>''],
                ['id'=>43, 'slug'=>'edit-category-module', 'description'=>''],
                ['id'=>44, 'slug'=>'delete-category-module', 'description'=>''],
                #Product Unit
                ['id'=>45, 'slug'=>'unit-module', 'description'=>''],
                ['id'=>46, 'slug'=>'create-unit-module', 'description'=>''],
                ['id'=>47, 'slug'=>'edit-unit-module', 'description'=>''],
                ['id'=>48, 'slug'=>'delete-unit-module', 'description'=>''],
                #Stock Balance
                ['id'=>49, 'slug'=>'stock-balance-module', 'description'=>''],
                ['id'=>50, 'slug'=>'create-stock-balance-module', 'description'=>''],
                ['id'=>51, 'slug'=>'edit-stock-balance-module', 'description'=>''],
                ['id'=>52, 'slug'=>'delete-stock-balance-module', 'description'=>''],

                //Finance
                #Asset
                ['id'=>53, 'slug'=>'asset-module', 'description'=>''],
                ['id'=>54, 'slug'=>'create-asset-module', 'description'=>''],
                ['id'=>55, 'slug'=>'edit-asset-module', 'description'=>''],
                ['id'=>56, 'slug'=>'delete-asset-module', 'description'=>''],
                #Bank
                ['id'=>57, 'slug'=>'bank-module', 'description'=>''],
                ['id'=>58, 'slug'=>'create-bank-module', 'description'=>''],
                ['id'=>59, 'slug'=>'edit-bank-module', 'description'=>''],
                ['id'=>60, 'slug'=>'delete-bank-module', 'description'=>''],
                #Cash
                ['id'=>61, 'slug'=>'cash-module', 'description'=>''],
                ['id'=>62, 'slug'=>'create-cash-module', 'description'=>''],
                ['id'=>63, 'slug'=>'edit-cash-module', 'description'=>''],
                ['id'=>64, 'slug'=>'delete-cash-module', 'description'=>''],
                #Cash Flow
                ['id'=>65, 'slug'=>'cash-flow-module', 'description'=>''],
                ['id'=>66, 'slug'=>'print-cash-flow-module', 'description'=>''],
                #Chart Account
                ['id'=>67, 'slug'=>'chart-account-module', 'description'=>''],
                ['id'=>68, 'slug'=>'create-chart-account-module', 'description'=>''],
                ['id'=>69, 'slug'=>'edit-chart-account-module', 'description'=>''],
                ['id'=>70, 'slug'=>'delete-chart-account-module', 'description'=>''],
                #Jurnal Umum
                ['id'=>71, 'slug'=>'jurnal-umum-module', 'description'=>''],
                ['id'=>72, 'slug'=>'create-jurnal-umum-module', 'description'=>''],
                ['id'=>73, 'slug'=>'edit-jurnal-umum-module', 'description'=>''],
                ['id'=>74, 'slug'=>'delete-jurnal-umum-module', 'description'=>''],
                #Ledger
                ['id'=>75, 'slug'=>'ledger-module', 'description'=>''],
                ['id'=>76, 'slug'=>'print-ledger-module', 'description'=>''],
                #Lost & Profit
                ['id'=>77, 'slug'=>'lost-profit-module', 'description'=>''],
                ['id'=>78, 'slug'=>'print-lost-profit-module', 'description'=>''],
                #List Hutang
                ['id'=>79, 'slug'=>'list-hutang-module', 'description'=>''],
                #List Piutang
                ['id'=>80, 'slug'=>'list-piutang-module', 'description'=>''],
                #Neraca
                ['id'=>81, 'slug'=>'neraca-module', 'description'=>''],
                ['id'=>82, 'slug'=>'print-neraca-module', 'description'=>''],
                #Report
                ['id'=>83, 'slug'=>'report-module', 'description'=>''],
                ['id'=>84, 'slug'=>'print-report-module', 'description'=>''],
                #Supplier
                ['id'=>85, 'slug'=>'supplier-module', 'description'=>''],
                ['id'=>86, 'slug'=>'create-supplier-module', 'description'=>''],
                ['id'=>87, 'slug'=>'edit-supplier-module', 'description'=>''],
                ['id'=>88, 'slug'=>'delete-supplier-module', 'description'=>''],
                ['id'=>89, 'slug'=>'create-purchase-invoice-supplier-module', 'description'=>''],
                #Customer
                ['id'=>90, 'slug'=>'customer-module', 'description'=>''],
                ['id'=>91, 'slug'=>'create-customer-module', 'description'=>''],
                ['id'=>92, 'slug'=>'edit-customer-module', 'description'=>''],
                ['id'=>93, 'slug'=>'delete-customer-module', 'description'=>''],
                ['id'=>94, 'slug'=>'create-sales-invoice-customer-module', 'description'=>''],
                #Invoice Term
                ['id'=>95, 'slug'=>'invoice-term-module', 'description'=>''],
                ['id'=>96, 'slug'=>'create-invoice-term-module', 'description'=>''],
                ['id'=>97, 'slug'=>'edit-invoice-term-module', 'description'=>''],
                ['id'=>98, 'slug'=>'delete-invoice-term-module', 'description'=>''],
                #Driver Term
                ['id'=>99, 'slug'=>'driver-module', 'description'=>''],
                ['id'=>100, 'slug'=>'create-driver-module', 'description'=>''],
                ['id'=>101, 'slug'=>'edit-driver-module', 'description'=>''],
                ['id'=>102, 'slug'=>'delete-driver-module', 'description'=>''],
                #Vehicle Term
                ['id'=>103, 'slug'=>'vehicle-module', 'description'=>''],
                ['id'=>104, 'slug'=>'create-vehicle-module', 'description'=>''],
                ['id'=>105, 'slug'=>'edit-vehicle-module', 'description'=>''],
                ['id'=>106, 'slug'=>'delete-vehicle-module', 'description'=>''],
                #Users list
                ['id'=>107, 'slug'=>'user-list-module', 'description'=>''],
                ['id'=>108, 'slug'=>'create-user-list-module', 'description'=>''],
                ['id'=>109, 'slug'=>'edit-user-list-module', 'description'=>''],
                ['id'=>110, 'slug'=>'delete-user-list-module', 'description'=>''],
                #Role
                ['id'=>111, 'slug'=>'role-module', 'description'=>''],
                ['id'=>112, 'slug'=>'create-role-module', 'description'=>''],
                ['id'=>113, 'slug'=>'edit-role-module', 'description'=>''],
                ['id'=>114, 'slug'=>'delete-role-module', 'description'=>''],
                #Permission
                ['id'=>115, 'slug'=>'permission-module', 'description'=>''],
                ['id'=>116, 'slug'=>'edit-permission-module', 'description'=>''],
                #Backup
                ['id'=>117, 'slug'=>'backup-module', 'description'=>''],
                ['id'=>118, 'slug'=>'restore-module', 'description'=>''],

        ];
        DB::table('permissions')->insert($permissions);
        //ENDBlock table permissions

        //Block table permission_role
        DB::table('permission_role')->delete();
        $permission_role = [
        	//Administrator privilleges
        	['permission_id'=>1, 'role_id'=>2],
        	['permission_id'=>2, 'role_id'=>2],
        	['permission_id'=>3, 'role_id'=>2],
        	['permission_id'=>4, 'role_id'=>2],
            ['permission_id'=>5, 'role_id'=>2],
            ['permission_id'=>6, 'role_id'=>2],
            ['permission_id'=>7, 'role_id'=>2],
            ['permission_id'=>8, 'role_id'=>2],
            ['permission_id'=>9, 'role_id'=>2],
            ['permission_id'=>12, 'role_id'=>2],

            //Finance Privillages
            ['permission_id'=>1, 'role_id'=>3],
            ['permission_id'=>2, 'role_id'=>3],
            ['permission_id'=>3, 'role_id'=>3],
            ['permission_id'=>4, 'role_id'=>3],
            ['permission_id'=>5, 'role_id'=>3],
            ['permission_id'=>6, 'role_id'=>3],
        ];
        DB::table('permission_role')->insert($permission_role);
        //ENDBlock table permission_role



    }
}
