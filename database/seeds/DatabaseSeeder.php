<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(PurchaseOrdersTableSeeder::class);
        $this->call(PurchaseOrderInvoiceTableSeeder::class);
        $this->call(PurchaseReturnTableSeeder::class);
        $this->call(FamiliesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(SalesOrdersTableSeeder::class);
        $this->call(InvoceTermsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(BankTableSeeder::class);
        $this->call(StockBalanceTableSeeder::class);
        $this->call(AclTableSeeder::class);
    }
}
