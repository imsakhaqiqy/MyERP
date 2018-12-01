<?php


Route::get('/', function () {
    return view('welcome');
});

//API testing calls
Route::post('wp_post', 'PurchaseOrderController@wp_post');
Route::any('/show_product', function(){
	$product = \DB::table('products')->get();
	return json_encode($product);
});
//ENDjust an API testing calls

Route::auth();

Route::get('/home', 'HomeController@index');

//restore
Route::resource('restore','RestoreController');

//backup
Route::resource('backup','BackUpController');

//purchase-giro
Route::post('approvePurchaseGiro','PurchaseGiroController@approvePurchaseGiro');
Route::resource('purchase-giro','PurchaseGiroController');

//sales-giro
Route::post('approveSalesGiro','GiroController@approveSalesGiro');
Route::resource('sales-giro','GiroController');

//adjustment
Route::post('callFieldProduct','ProductAdjusmentController@callSubProduct');
Route::post('deleteAdjustment','ProductAdjusmentController@destroy');
Route::resource('product-adjustment','ProductAdjusmentController');
//cash flow
Route::post('cash-flow.cash_flow_print','CashFlowController@cash_flow_print');
Route::post('cash-flow/search','CashFlowController@cash_flow_search');
Route::resource('cash-flow','CashFlowController');
//ledger
Route::post('ledger.ledger_print','LedgerController@ledger_print');
Route::post('ledger/search','LedgerController@ledger_search');
Route::resource('ledger','LedgerController');
//asset
Route::post('deleteAsset','AssetController@destroy');
Route::resource('asset','AssetController');
//report
Route::post('report/search','ReportController@report_search');
Route::post('report.report_print','ReportController@report_print');
Route::resource('report','ReportController');

//biaya operasi
Route::post('deleteTransChartAccount','BiayaOperasiController@destroy');
Route::resource('biaya-operasi','BiayaOperasiController');

//lost&profit
Route::post('lost-profit/submit','LostProfitController@lost_profit_sort_submit');
Route::post('lost-profit.lost_profit_print','LostProfitController@lost_profit_print');
Route::resource('lost-profit','LostProfitController');

//family
Route::post('deleteFamily','FamilyController@destroy');
Route::resource('family','FamilyController');

//main product
Route::post('callCategory','MainProductController@callCategory');
Route::get('product-all','MainProductController@product_all');
Route::get('product-available','MainProductController@product_available');
Route::post('deleteMainProduct','MainProductController@destroy');
Route::post('main-product.destroy_product','MainProductController@destroy_product');
Route::put('main-product.update_product','MainProductController@update_product');
Route::post('main-product.store_product','MainProductController@store_product');
Route::get('main-product/{id}/show','MainProductController@show_product');
Route::resource('main-product','MainProductController');


//neraca
Route::post('neraca/submit','NeracaController@neraca_sort_submit');
Route::post('neraca.neraca_print','NeracaController@neraca_print');
Route::resource('neraca','NeracaController');

//sub chart account
Route::post('sub-chart-account.store_saldo_awal','ChartAccountController@store_saldo_awal');
Route::post('deleteSubChartAccount','ChartAccountController@delete_sub');
Route::put('sub-chart-account.update_sub','ChartAccountController@update_sub');
Route::post('sub-chart-account.store_sub','ChartAccountController@store_sub');
Route::resource('sub-chart-account','ChartAccountController');

//chart account
Route::post('deleteChartAccount','ChartAccountController@destroy');
Route::resource('chart-account','ChartAccountController');

// vehicle
Route::post('deleteVehicle','VehicleController@destroy');
Route::resource('vehicle','VehicleController');

// Cash
Route::post('deleteCash','CashController@destroy');
Route::resource('cash','CashController');

// Sales Return
    //Send sales return
    Route::post('acceptSalesReturn', 'SalesReturnController@changeToAccept');
    //Complete sales return
    Route::post('resentSalesReturn','SalesReturnController@changeToResent');
    //Save sales Return
    Route::post('storeSalesReturn', 'SalesReturnController@store');
    //Delete sales Return
    Route::post('deleteSalesReturn','SalesReturnController@destroy');
    Route::resource('sales-return', 'SalesReturnController');




//Bank
Route::post('deleteBank', 'BankController@destroy');
Route::resource('bank', 'BankController');

//Category
Route::post('deleteCategory', 'CategoryController@destroy');
Route::resource('category', 'CategoryController');

//Stock Balance
//Update
Route::get('stock_balance/{id}/printPdf','StockBalanceController@printPdf');
Route::get('stock_balance/print','StockBalanceController@printStockBalance');
Route::put('UpdateSalesOrder', 'SalesOrderController@update');
Route::post('deleteStockBalance','StockBalanceController@destroy');
Route::resource('stock_balance','StockBalanceController');

//Driver
Route::get('/func',function(){
    return Helper::uc_words("imsak haqiqy");
});
Route::post('deleteDriver', 'DriverController@destroy');
Route::resource('driver','DriverController');

//Product
Route::post('check_product_availability', 'ProductController@check_product_availability');
Route::post('deleteProduct', 'ProductController@destroy');
Route::resource('product', 'ProductController');

//Suppliers
Route::post('supplier.store_invoice_payment_giro','SupplierController@store_invoice_payment_giro');
Route::post('supplier.store_invoice_payment_bank','SupplierController@store_invoice_payment_bank');
Route::post('supplier.store_invoice_payment_cash','SupplierController@store_invoice_payment_cash');
Route::get('supplier/{id}/payment-invoices','SupplierController@payment_invoices');
Route::post('deleteSupplier', 'SupplierController@destroy');
Route::resource('supplier', 'SupplierController');

//Unit
Route::post('deleteUnit', 'UnitController@destroy');
Route::resource('unit', 'UnitController');


//Customer
Route::post('customer.store_invoice_payment_giro','CustomerController@store_invoice_payment_giro');
Route::post('customer.store_invoice_payment_cash','CustomerController@store_invoice_payment_cash');
Route::post('customer.store_invoice_payment_bank','CustomerController@store_invoice_payment_bank');
Route::post('customer.store_invoice_payment_cash','CustomerController@store_invoice_payment_cash');
Route::get('customer/{id}/payment-invoices','CustomerController@payment_invoices');
Route::post('deleteCustomer', 'CustomerController@destroy');
Route::resource('customer', 'CustomerController');

//Purchase orders
  //purchase hutang
  Route::get('purchase-hutang','PurchaseOrderController@list_hutang');
  //call sub product
  Route::post('callSubProduct','PurchaseOrderController@callSubProduct');
	//complete purchase order
	Route::post('completePurchaseOrder', 'PurchaseOrderController@complete');
	//accept purchase order
	Route::post('acceptPurchaseOrder', 'PurchaseOrderController@accept');
	//delete
	Route::post('deletePurchaseOrder', 'PurchaseOrderController@destroy');
	//Print
	Route::get('purchase-order/{id}/printPdf', 'PurchaseOrderController@printPdf');
	//Save Purchase Order
	Route::post('storePurchaseOrder', 'PurchaseOrderController@store');
	//Update
	Route::put('UpdatePurchaseOrder', 'PurchaseOrderController@update');
	Route::resource('purchase-order', 'PurchaseOrderController'); //

//Purchase Order Invoice
  Route::post('storePurchasePaymentGiro', 'PurchaseOrderInvoiceController@storePaymentGiro');
	Route::post('storePurchasePaymentTransfer', 'PurchaseOrderInvoiceController@storePaymentTransfer');
	Route::post('storePurchasePaymentCash', 'PurchaseOrderInvoiceController@storePaymentCash');
	Route::post('completePurchaseInvoice', 'PurchaseOrderInvoiceController@completePurchaseInvoice');
  Route::post('completePurchaseAccount','PurchaseOrderInvoiceController@completePurchaseAccount');
	Route::get('purchase-order-invoice/{invoice_id}/payment/create', 'PurchaseOrderInvoiceController@createPayment');
	Route::get('purchase-order-invoice/{purchase_order_id}/create', 'PurchaseOrderInvoiceController@create');
	Route::post('storePurchaseOrderInvoice', 'PurchaseOrderInvoiceController@store');
	Route::post('deletePurchaseOrderInvoice', 'PurchaseOrderInvoiceController@destroy');
	Route::put('updatePurchaseOrderInvoice', 'PurchaseOrderInvoiceController@update');
	Route::resource('purchase-order-invoice', 'PurchaseOrderInvoiceController');

//Purchase Return
	//complete purchase return
	Route::post('completePurchaseReturn', 'PurchaseReturnController@changeToCompleted');
	//Send purchase return
	Route::post('sendPurchaseReturn', 'PurchaseReturnController@changeToSent');
	//Save Purchase Return
	Route::post('storePurchaseReturn', 'PurchaseReturnController@store');
  Route::post('deletePurchaseReturn','PurchaseReturnController@destroy');
	Route::resource('purchase-return', 'PurchaseReturnController');


//Sales Order
  //sales piutang
  Route::get('sales-piutang','SalesOrderController@list_piutang');
  //call sub product
  Route::post('callSubProduct','SalesOrderController@callSubProduct');
	//Save
	Route::post('storeSalesOrder', 'SalesOrderController@store');
	//Update sales order status
	Route::post('sales-order/updateStatus', 'SalesOrderController@updateStatus');
	//Update
	Route::put('UpdateSalesOrder', 'SalesOrderController@update');
	//delete
	Route::post('deleteSalesOrder', 'SalesOrderController@destroy');
    //Print
	Route::get('sales-order/{id}/printPdf', 'SalesOrderController@printPdf');
    Route::get('sales-order/{id}/printDO','SalesOrderController@printDO');
	Route::resource('sales-order', 'SalesOrderController');

//Sales order invoice
  Route::post('storeSalesPaymentGiro','SalesOrderInvoiceController@storePaymentGiro');
  Route::post('storeSalesPaymentCash','SalesOrderInvoiceController@storePaymentCash');
  Route::post('storeSalesPaymentTransfer','SalesOrderInvoiceController@storePaymentTransfer');
	Route::post('completeSalesInvoice', 'SalesOrderInvoiceController@completeSalesInvoice');
  Route::post('completeSalesAccount','SalesOrderInvoiceController@completeSalesAccount');
	Route::post('deleteSalesOrderInvoice', 'SalesOrderInvoiceController@destroy');
	Route::post('storeSalesOrderInvoice', 'SalesOrderInvoiceController@store');
	Route::get('sales-order-invoice/{sales_order_id}/create', 'SalesOrderInvoiceController@create');
	Route::get('sales-order-invoice/{invoice_id}/payment/create', 'SalesOrderInvoiceController@createPayment');
	Route::post('storeInvoicePayment', 'SalesOrderInvoiceController@storeInvoicePayment');
    //print
    Route::get('sales-order-invoice/{id}/printInv','SalesOrderInvoiceController@printInv');
	Route::resource('sales-order-invoice', 'SalesOrderInvoiceController');

//Invoiceterms
  Route::post('deleteInvoiceTerm','InvoiceTermController@destroy');
	Route::resource('invoice-term', 'InvoiceTermController');


//Users
	Route::resource('user', 'UserController');
    Route::post('deleteUser','UserController@destroy');
//Roles
    Route::post('deleteRole','RoleController@destroy');
	Route::post('update-role-permission', 'RoleController@updateRolePermission');
	Route::resource('role', 'RoleController');

//Permission
	Route::resource('permission','PermissionController');

Route::controller('datatables', 'DatatablesController',[
	'getRoles'=>'datatables.getRoles',
	'getPermissions'=>'datatables.getPermissions',
	'getUsers'=>'datatables.getUsers',
	'getProducts'=>'datatables.getProducts',
	'getSuppliers'=>'datatables.getSuppliers',
	'getUnits'=>'datatables.getUnits',
	'getPurchaseOrders'=>'datatables.getPurchaseOrders',
	'getPurchaseOrderInvoices'=>'datatables.getPurchaseOrderInvoices',
	'getSalesOrders'=>'datatables.getSalesOrders',
	'getSalesOrderInvoices'=>'datatables.getSalesOrderInvoices',
	'getPurchaseReturns'=>'datatables.getPurchaseReturns',
    'getSalesReturns'=>'datatables.getSalesReturns',
	'getCustomers'=>'datatables.getCustomers',
	'getInvoiceTerms'=>'datatables.getInvoiceTerms',
    'getDrivers'=>'datatables.getDrivers',

    'getStockBalances' => 'datatables.getStockBalances',

    'getBanks'=>'datatables.getBanks',
    'getCashs'=>'datatables.getCashs',
    'getVehicles' =>'datatables.getVehicles',
    'getChartAccounts' =>'datatables.getChartAccounts',
    'getMainProducts' =>'datatables.getMainProducts',
    'getSubChartAccounts' =>'datatables.getSubChartAccounts',
    'getTransactionChartAccounts' =>'datatables.getTransactionChartAccounts',
    'getAssets'=>'datatables.getAssets',
    'getAdjustments'=>'datatables.getAdjustments',
    'getPurchaseGiros'=>'datatables.getPurchaseGiros',
    'getSalesGiros'=>'datatables.getSalesGiros'
]);
