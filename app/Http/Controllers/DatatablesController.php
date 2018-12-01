<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Yajra\Datatables\Datatables;

use App\User;
use App\Role;
use App\Permission;
use App\Product;
use App\Supplier;
use App\Customer;
use App\Unit;
use App\PurchaseOrder;
use App\PurchaseOrderInvoice;
use App\PurchaseReturn;
use App\SalesOrder;
use App\SalesOrderInvoice;
use App\SalesReturn;
use App\InvoiceTerm;
use App\Driver;
use App\StockBalance;
use App\Bank;
use App\Cash;
use App\Vehicle;
use App\ChartAccount;
use App\SubChartAccount;
use App\MainProduct;
use App\TransactionChartAccount;
use App\Asset;
use App\Adjustment;
use App\GiroPurchaseInvoicePayment;
use App\GiroSalesInvoicePayment;

class DatatablesController extends Controller
{



    //Function get CUSTOMERS datatable
    public function getCustomers(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $customers = Customer::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'name',
            'phone_number',
            'address',
            'invoice_term_id'
        ]);

        $data_customers = Datatables::of($customers)
            ->editColumn('name', function($customers){
                $actions_html = '<a href="'.url('customer/'.$customers->id.'/payment-invoices').'" class="btn btn-default btn-xs pull-right" title="Create payment for this invoice">';
                $actions_html .='<i class="fa fa-money"></i>';
                $actions_html .='</a>';
                return $customers->name.$actions_html;
            })
            ->editColumn('invoice_term_id', function($customers){
                if(!is_null($customers->invoice_term_id)){
                    return $customers->invoice_term->name;
                }
                return "";
            })
            ->addColumn('actions', function($customers){
                    $actions_html ='<a href="'.url('customer/'.$customers->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('customer/'.$customers->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this customer">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-customer-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-customer" data-id="'.$customers->id.'" data-text="'.$customers->name.'" title="Click to delete this customer">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_customers->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_customers->make(true);
    }
    //ENDFunction get CUSTOMERS datatable


    //Function to get product list
    public function getProducts(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $products = Product::with('main_product','family')->select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'description',
            'stock',
            'minimum_stock',
            'main_product_id',
        ]);
        $datatables = Datatables::of($products)
        ->editColumn('main_products_name', function($products){
            return $products->main_product->name;
        })
        ->editColumn('family_id', function($products){
            return $products->main_product->family->name;
        })
        ->editColumn('category_id', function($products){
            return $products->main_product->category->name;
        })
        ->editColumn('unit_id', function($products){
            return $products->main_product->unit->name;
        })
        ->editColumn('stock', function($products){
            return $products->stock;
        });

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);

    }
    //ENDFunction to get product list


    //Function to get main product list
    public function getMainProducts(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $main_products = MainProduct::with('product')->select([
            \DB::raw('@rownum := @rownum + 1 AS rownum'),
            'id',
            'code',
            'name',
            'family_id',
            'category_id',
            'unit_id',
            'image'
        ]);
        $data_main_products = Datatables::of($main_products)
            ->editColumn('code', function($main_products){
                return $main_products->code;
            })
            ->editColumn('family_id', function($main_products){
                return $main_products->family->name;
            })
            ->editColumn('category_id', function($main_products){
                return $main_products->category->name;
            })
            ->editColumn('unit_id', function($main_products){
                return $main_products->unit->name;
            })
            ->editColumn('description', function($main_products){
                return $main_products->product->first()->description;
            })
            ->editColumn('image', function($main_products){
                $actions_html = '';
                if($main_products->image != NULL){
                    $actions_html = '<a href="#" class="thumbnail"><img src="'.url('img/products/thumb_'.$main_products->image).'"></a>';
                }
                return $actions_html;
            })
            ->addColumn('actions', function($main_products){
                    $actions_html  ='<a href="'.url('main-product/'.$main_products->id.'/show').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('main-product/'.$main_products->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this product">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-product-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-main-product" data-id="'.$main_products->id.'" data-text="'.$main_products->name.'" title="Click to delete this product">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                    return $actions_html;
            });
            if($keyword = $request->get('search')['value']){
                $data_main_products->filterColumn('rownum','whereRaw','@rownum + 1 like ?', ["%{$keyword}%"]);
            }
            return $data_main_products->make(true);
    }
    //ENDFunction to get main product list

    //Function to get UNITS list
    public function getUnits(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $units = Unit::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'created_at',
            'updated_at',
        ]);

        $data_units = Datatables::of($units)
            ->addColumn('actions', function($units){
                    $actions_html ='<a href="'.url('unit/'.$units->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('unit/'.$units->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this unit product">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-unit-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-unit" data-id="'.$units->id.'" data-text="'.$units->name.'" title="Click to delete this unit prosuct">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_units->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_units->make(true);
    }
    //ENDFunction to get UNITS list

    //Function to get supplier list
    public function getSuppliers(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $suppliers = Supplier::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'name',
            'pic_name',
            'primary_email',
            'primary_phone_number',
        ]);
        $data_suppliers = Datatables::of($suppliers)
            ->editColumn('name', function($suppliers){
                $actions_html = '<a href="'.url('supplier/'.$suppliers->id.'/payment-invoices').'" class="btn btn-default btn-xs pull-right" title="Create payment for this invoice">';
                $actions_html .='<i class="fa fa-money"></i>';
                $actions_html .='</a>';
                return $suppliers->name.$actions_html;
            })
            ->addColumn('actions', function($suppliers){
                    $actions_html ='<a href="'.url('supplier/'.$suppliers->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('supplier/'.$suppliers->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this supplier">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-supplier-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-supplier" data-id="'.$suppliers->id.'" data-text="'.$suppliers->name.'" title="Click to delete this supplier">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_suppliers->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }


        return $data_suppliers->make(true);

    }
    //ENDFunction to get supplier list


    //Function get Purchase Orders list
    public function getPurchaseOrders(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $purchase_orders = PurchaseOrder::with('supplier', 'created_by', 'purchase_order_invoice')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'purchase_orders.*',
            ]
        );

        $data_purchase_orders = Datatables::of($purchase_orders)
            ->editColumn('supplier_id', function($purchase_orders){
                return $purchase_orders->supplier->name;
            })
            ->editColumn('creator', function($purchase_orders){
                return $purchase_orders->created_by->name;
            })
            ->editColumn('status', function($purchase_orders){
                return strtoupper($purchase_orders->status);
            })
            ->editColumn('invoice', function($purchase_orders){
                if(count($purchase_orders->purchase_order_invoice) == 1){
                    $btn_inv  = '<a href="'.url('purchase-order-invoice/'.$purchase_orders->purchase_order_invoice->id.'').'" class="btn btn-info btn-xs" title="Click to view the invoice detail">';
                    $btn_inv .= $purchase_orders->purchase_order_invoice->code;
                    $btn_inv .= '</a>&nbsp;';
                    return 'Available'.' '.$btn_inv;
                }else{
                    return 'Unavailable';
                }
            })
            ->addColumn('actions', function($purchase_orders){
                $actions_html ='<a href="'.url('purchase-order/'.$purchase_orders->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                //only show edit button link if the status is posted
                if($purchase_orders->status =='posted'){
                    $actions_html .='<a href="'.url('purchase-order/'.$purchase_orders->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                }
                if(count($purchase_orders->purchase_order_invoice) > 0){
                    if(\Auth::user()->can('delete-purchase-order-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-order" title="Click to delete" data-id="'.$purchase_orders->id.'" data-text="'.$purchase_orders->code.'" data-id-payment="'.$purchase_orders->purchase_order_invoice->id.'" data-code-invoice="'.$purchase_orders->purchase_order_invoice->code.'" data-id-invoice="'.$purchase_orders->purchase_order_invoice->id.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }else{
                    if(\Auth::user()->can('delete-purchase-order-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-order" title="Click to delete" data-id="'.$purchase_orders->id.'" data-text="'.$purchase_orders->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }
                return $actions_html;
            });

        // if ($keyword = $request->get('search')['value']) {
        //     $data_purchase_orders->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        // }

        return $data_purchase_orders->make(true);

    }
    //ENDFunction get Purchase Orders list


    //Function get Purchase Order Invoice
    public function getPurchaseOrderInvoices(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $purchase_order_invoices = PurchaseOrderInvoice::with('purchase_order','creator', 'payment_method')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'purchase_order_invoices.*',
            ]
        );
        $data_purchase_order_invoices = Datatables::of($purchase_order_invoices)
            ->editColumn('code', function($purchase_order_invoices){
                $btn_pur  = '<a href="'.url('purchase-order/'.$purchase_order_invoices->purchase_order_id.'').'" class="btn btn-info btn-xs" title="Click to view the purchase order detail">';
                $btn_pur .= $purchase_order_invoices->purchase_order->code;
                $btn_pur .= '</a>&nbsp;';
                return $purchase_order_invoices->code.'<br>'.$btn_pur;
            })
            ->editColumn('bill_price', function($purchase_order_invoices){
                return number_format($purchase_order_invoices->bill_price);
            })
            ->editColumn('paid_price', function($purchase_order_invoices){
                return number_format($purchase_order_invoices->paid_price);
            })
            ->editColumn('creator', function($purchase_order_invoices){

                return $purchase_order_invoices->creator->name;
            })
            ->editColumn('term', function($purchase_order_invoices){
                return $purchase_order_invoices->term;
            })
            ->editColumn('debt', function($purchase_order_invoices){
                return (number_format($purchase_order_invoices->bill_price-$purchase_order_invoices->paid_price));
            })
            ->editColumn('status', function($purchase_order_invoices){

                return strtoupper($purchase_order_invoices->status);
            })
            ->addColumn('actions', function($purchase_order_invoices){
                $actions_html ='<a href="'.url('purchase-order-invoice/'.$purchase_order_invoices->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                if($purchase_order_invoices->status != "completed"){
                    $actions_html .='<a href="'.url('purchase-order-invoice/'.$purchase_order_invoices->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                }
                if(\Auth::user()->can('delete-purchase-order-invoice-module'))
                {
                    $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-order-invoice" data-id="'.$purchase_order_invoices->id.'" data-text="'.$purchase_order_invoices->code.'" title="Click to delete">';
                    $actions_html .=    '<i class="fa fa-trash"></i>';
                    $actions_html .='</button>';
                }

                return $actions_html;
            });
        return $data_purchase_order_invoices->make(true);
    }
    //ENDFunction get Purchase Order Invoice


    //Function get Purchase Returns
    public function getPurchaseReturns(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $purchase_returns = PurchaseReturn::with('purchase_order','creator', 'product')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'purchase_returns.*',
            ]
        );
        $data_purchase_returns = Datatables::of($purchase_returns)
            ->editColumn('purchase_order_id', function($purchase_returns){
                $btn_inv  = '<a href="'.url('purchase-order/'.$purchase_returns->purchase_order_id.'').'" class="btn btn-info btn-xs" title="Click to view the purchase order detail">';
                $btn_inv .= $purchase_returns->purchase_order->code;
                $btn_inv .= '</a>&nbsp;';
                return $btn_inv;
            })
            ->editColumn('product_id', function($purchase_returns){
                return $purchase_returns->product->name;
            })
            ->editColumn('status', function($purchase_returns){
                $status_label = '';
                $status_action = '';
                if($purchase_returns->status =='posted'){
                    $status_label = '<p>POSTED</p>';
                    $status_action .='<button type="button" class="btn btn-warning btn-xs btn-send-purchase-return" data-id="'.$purchase_returns->id.'" title="Change status to Sent">';
                    $status_action .=    '<i class="fa fa-sign-in"></i>';
                    $status_action .='</button>';
                }
                else if($purchase_returns->status =='sent'){
                    $status_label = '<p>SENT</p>';
                    $status_action .='<button type="button" class="btn btn-success btn-xs btn-complete-purchase-return" data-id="'.$purchase_returns->id.'" title="Change status to Completed">';
                    $status_action .=    '<i class="fa fa-check"></i>';
                    $status_action .='</button>';
                }
                else{
                    $status_label = '<p>COMPLETED</p>';
                }

                return $status_label;
            })
            ->editColumn('supplier_name', function($purchase_returns){
                return $purchase_returns->purchase_order->supplier->name;
            })
            ->addColumn('actions', function($purchase_returns){
                //only provide edit and delete button if the purchase return status is posted, otherwise DO NOT show them
                $actions_html = '';
                if($purchase_returns->status == 'posted'){
                    $actions_html ='<a href="'.url('purchase-return/'.$purchase_returns->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('purchase-return/'.$purchase_returns->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-purchase-order-return-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-return" data-id="'.$purchase_returns->id.'" data-text="'.$purchase_returns->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }elseif ($purchase_returns->status == 'sent') {
                    $actions_html ='<a href="'.url('purchase-return/'.$purchase_returns->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-purchase-order-return-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-return" data-id="'.$purchase_returns->id.'" data-text="'.$purchase_returns->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }elseif ($purchase_returns->status == 'completed') {
                    $actions_html ='<a href="'.url('purchase-return/'.$purchase_returns->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-purchase-order-return-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-purchase-return" data-id="'.$purchase_returns->id.'" data-text="'.$purchase_returns->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }

                return $actions_html;
            });
        return $data_purchase_returns->make(true);
    }


    //Function get Sales Orders list
    public function getSalesOrders(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $sales_orders = SalesOrder::with('customer', 'created_by','sales_order_invoice')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'sales_orders.*',
            ]
        );

        $data_sales_orders = Datatables::of($sales_orders)
            ->editColumn('customer_id', function($sales_orders){
                return $sales_orders->customer->name;
            })
            ->editColumn('creator', function($sales_orders){
                return $sales_orders->created_by->name;
            })
            ->editColumn('status', function($sales_orders){

                return strtoupper($sales_orders->status);

                $status_label = '';

                if($sales_orders->status == 'posted'){
                    $status_label = '<p>POSTED</p>';

                }
                else if($sales_orders->status =='accepted'){
                    $status_label = '<p>ACCEPTED</p>';

                }
                else{
                    $status_label = '<p>COMPLETED</p>';
                }

                return $status_label;

            })
            ->editColumn('invoice', function($sales_orders){
                if(count($sales_orders->sales_order_invoice) == 1){
                    $btn_inv  = '<a href="'.url('sales-order-invoice/'.$sales_orders->sales_order_invoice->id.'').'" class="btn btn-info btn-xs" title="Click to view the invoice detail">';
                    $btn_inv .= $sales_orders->sales_order_invoice->code;
                    $btn_inv .= '</a>&nbsp;';
                    return 'Available'.' '.$btn_inv;
                }else{
                    return 'Unavailable';
                }
            })
            ->addColumn('actions', function($sales_orders){
                $actions_html ='<a href="'.url('sales-order/'.$sales_orders->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                //only show edit button link if the status is posted
                if($sales_orders->status =='posted'){
                    $actions_html .='<a href="'.url('sales-order/'.$sales_orders->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                }
                if(count($sales_orders->sales_order_invoice) > 0){
                    if(\Auth::user()->can('delete-sales-order-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-sales-order" data-id="'.$sales_orders->id.'" data-text="'.$sales_orders->code.'" data-id-payment="'.$sales_orders->sales_order_invoice->id.'" data-code-invoice="'.$sales_orders->sales_order_invoice->code.'" data-id-invoice="'.$sales_orders->sales_order_invoice->id.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }else{
                    if(\Auth::user()->can('delete-sales-order-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-sales-order" data-id="'.$sales_orders->id.'" data-text="'.$sales_orders->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }
                return $actions_html;
            });

        /*if ($keyword = $request->get('search')['value']) {
            $data_sales_orders->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }*/

        return $data_sales_orders->make(true);

    }
    //ENDFunction get Sales Orders list

    //Function get Sales Order Invoice
    public function getSalesOrderInvoices(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $sales_order_invoices = SalesOrderInvoice::with('sales_order','creator')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'sales_order_invoices.*',
            ]
        );
        $data_sales_order_invoices = Datatables::of($sales_order_invoices)
            ->editColumn('code', function($sales_order_invoices){
                $btn_inv  = '<a href="'.url('sales-order/'.$sales_order_invoices->sales_order_id.'').'" class="btn btn-info btn-xs" title="Click to view the sales order detail">';
                $btn_inv .= $sales_order_invoices->sales_order->code;
                $btn_inv .= '</a>&nbsp;';
                return $sales_order_invoices->code.' '.$btn_inv;
            })
            ->editColumn('sales_order_id', function($sales_order_invoices){
                return $sales_order_invoices->sales_order->code;
            })
            ->editColumn('bill_price', function($sales_order_invoices){
                return number_format($sales_order_invoices->bill_price);
            })
            ->editColumn('paid_price', function($sales_order_invoices){
                return number_format($sales_order_invoices->paid_price);
            })
            ->editColumn('created_by', function($sales_order_invoices){

                return $sales_order_invoices->creator->name;
            })
            ->editColumn('due_date', function($sales_order_invoices){
                return $sales_order_invoices->due_date;
            })
            ->editColumn('debt', function($sales_order_invoices){
                return (number_format($sales_order_invoices->bill_price-$sales_order_invoices->paid_price));
            })
            ->editColumn('status', function($sales_order_invoices){
                return strtoupper($sales_order_invoices->status);
            })
            ->addColumn('actions', function($sales_order_invoices){
                $actions_html ='<a href="'.url('sales-order-invoice/'.$sales_order_invoices->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                if($sales_order_invoices->status != 'completed'){
                    $actions_html .='<a href="'.url('sales-order-invoice/'.$sales_order_invoices->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                }
                if(\Auth::user()->can('delete-sales-order-invoice-module'))
                {
                    $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-sales-order-invoice" data-id="'.$sales_order_invoices->id.'" data-text="'.$sales_order_invoices->code.'" title="Click to delete">';
                    $actions_html .=    '<i class="fa fa-trash"></i>';
                    $actions_html .='</button>';
                }

                return $actions_html;
            });
        return $data_sales_order_invoices->make(true);
    }
    //ENDFunction get Sales Order Invoice

    //Function get sales return
    public function getSalesReturns(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $sales_returns = SalesReturn::with('sales_order','creator','product')->select(
            [
                \DB::raw('@rownum := @rownum + 1 AS rownum'),
                'sales_returns.*',
            ]
        );
        $data_sales_returns = Datatables::of($sales_returns)
            ->editColumn('sales_order_id', function($sales_returns){
                $btn_inv  = '<a href="'.url('sales-order/'.$sales_returns->sales_order_id.'').'" class="btn btn-info btn-xs" title="Click to view the sales order detail">';
                $btn_inv .= $sales_returns->sales_order->code;
                $btn_inv .= '</a>&nbsp;';
                return $btn_inv;
            })
            ->editColumn('product_id', function($sales_returns){
                return $sales_returns->product->name;
            })
            ->editColumn('status', function($sales_returns){
                $status_label = '';
                $status_action = '';
                if($sales_returns->status =='posted'){
                    $status_label = '<p>POSTED</p>';
                    $status_action .='<button type="button" class="btn btn-warning btn-xs btn-accept-sales-return" data-id="'.$sales_returns->id.'" title="Change status to Accept">';
                    $status_action .=    '<i class="fa fa-sign-in"></i>';
                    $status_action .='</button>';
                }
                else if($sales_returns->status =='accept'){
                    $status_label = '<p>ACCEPT</p>';
                    $status_action .='<button type="button" class="btn btn-success btn-xs btn-resent-sales-return" data-id="'.$sales_returns->id.'" title="Change status to Resent">';
                    $status_action .=    '<i class="fa fa-check"></i>';
                    $status_action .='</button>';
                }
                else{
                    $status_label = '<p>RESENT</p>';
                }

                return $status_label;
            })
            ->editColumn('customer_name', function($sales_returns){
                return $sales_returns->sales_order->customer->name;
            })
            ->addColumn('actions', function($sales_returns){
                $actions_html ='<a href="'.url('sales-return/'.$sales_returns->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                //only provide edit and delete button if the purchase return status is posted, otherwise DO NOT show them
                if($sales_returns->status == 'posted'){
                    $actions_html .='<a href="'.url('sales-return/'.$sales_returns->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-sales-order-return-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-sales-return" data-id="'.$sales_returns->id.'" data-text="'.$sales_returns->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }elseif ($sales_returns->status == 'resent'){
                    if(\Auth::user()->can('delete-sales-order-return-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-sales-return" data-id="'.$sales_returns->id.'" data-text="'.$sales_returns->code.'" title="Click to delete">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }
                }

                return $actions_html;
            });
        return $data_sales_returns->make(true);
    }

    //get invoice terms list
    public function getInvoiceTerms(Request $request)
    {
       \DB::statement(\DB::raw('set @rownum=0'));
        $invoice_terms = InvoiceTerm::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'day_many',
        ]);

        $data_invoice_terms = Datatables::of($invoice_terms)
            ->addColumn('actions', function($invoice_terms){
                    $actions_html ='<a href="'.url('invoice-term/'.$invoice_terms->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('invoice-term/'.$invoice_terms->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this invoice term">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-invoice-term-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-invoice-term" data-id="'.$invoice_terms->id.'" data-text="'.$invoice_terms->name.'" title="Click to delete this invoice term">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_invoice_terms->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_invoice_terms->make(true);
    }

    //Function to get driver list
    public function getDrivers(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        //\DB::table('suppliers')->orderBy('code','asc')->get();
        $drivers = Driver::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'name',
            'contact_number',
        ]);

        $data_drivers = Datatables::of($drivers)
            ->addColumn('actions', function($drivers){
                    $actions_html ='<a href="'.url('driver/'.$drivers->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('driver/'.$drivers->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this driver">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-driver-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-driver" data-id="'.$drivers->id.'" data-text="'.$drivers->name.'" title="Click to delete this driver">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_drivers->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_drivers->make(true);

    }
    //ENDFunction to get driver list

    //Function to get driver list
    public function getStockBalances(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $stock_balance = StockBalance::with('creator')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'stock_balances.*',
            ]
        );


        $data_stock_balance = Datatables::of($stock_balance)
            ->addColumn('actions', function($stock_balance){
                    $actions_html ='<a href="'.url('stock_balance/'.$stock_balance->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('stock_balance/'.$stock_balance->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this stock balance">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-stock-balance-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-stock-balance" data-id="'.$stock_balance->id.'" data-text="'.$stock_balance->code.'" title="Click to delete this stock balance">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            })
            ->editColumn('creator', function($stock_balance){
                    return $stock_balance->creator->name;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_stock_balance->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_stock_balance->make(true);

    }
    //ENDFunction to get stock balances list

    //function to get banks datatable
    public function getBanks(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $banks = Bank::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'name',
            'account_name',
            'account_number',
            'value'
        ]);

        $data_banks = Datatables::of($banks)
            ->addColumn('actions', function($banks){
                    $actions_html ='<a href="'.url('bank/'.$banks->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('bank/'.$banks->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this bank">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-bank-module'))
                    {
                        $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-bank" data-id="'.$banks->id.'" data-text="'.$banks->name.'" title="Click to delete this bank">';
                        $actions_html .=    '<i class="fa fa-trash"></i>';
                        $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_banks->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_banks->make(true);
    }

    public function getCashs(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $cashs = Cash::select([
            \DB::raw('@rownum := @rownum + 1 AS rownum'),
            'id',
            'code',
            'name',
            'value',
        ]);

        $data_cashs = Datatables::of($cashs)
            ->editColumn('value', function($cashs){
                return number_format($cashs->value);
            })
            ->addColumn('actions',function($cashs){
                $actions_html ='<a href="'.url('cash/'.$cashs->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                $actions_html .='<a href="'.url('cash/'.$cashs->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this cash">';
                $actions_html .=    '<i class="fa fa-edit"></i>';
                $actions_html .='</a>&nbsp;';
                if(\Auth::user()->can('delete-cash-module'))
                {
                    $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-cash" data-id="'.$cashs->id.'" data-text="'.$cashs->name.'" title="Click to delete this cash">';
                    $actions_html .=    '<i class="fa fa-trash"></i>';
                    $actions_html .='</button>';
                }

                return $actions_html;
            });

            if ($keyword = $request->get('search')['value']) {
                $data_cashs->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
            }

            return $data_cashs->make(true);
    }


    //Build users data
    public function getUsers(Request $request){

        \DB::statement(\DB::raw('set @rownum=0'));
        $users = User::with('roles')->select(
            [
                \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'users.*',
            ]
        );
        $datatables = Datatables::of($users)
            ->addColumn('role', function($users){
                return $users->roles->first()->name;
            })
            ->addColumn('actions', function($users){
                    $actions_html ='<a href="'.url('user/'.$users->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('user/'.$users->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this user">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-user-list-module'))
                    {
                      $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-user" data-id="'.$users->id.'" data-text="'.$users->name.'">';
                      $actions_html .=    '<i class="fa fa-trash"></i>';
                      $actions_html .='</button>';
                    }

                    return $actions_html;
            });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }

    //Build roles data
    public function getRoles(Request $request){

        \DB::statement(\DB::raw('set @rownum=0'));
        $roles = Role::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
        ])->where('name','!=', 'Super Admin');
        $datatables = Datatables::of($roles)

            ->addColumn('actions', function($roles){
                    $actions_html ='<a href="'.url('role/'.$roles->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('role/'.$roles->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this role">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-role" data-id="'.$roles->id.'" data-text="'.$roles->name.'">';
                    $actions_html .=    '<i class="fa fa-trash"></i>';
                    $actions_html .='</button>';

                    return $actions_html;
            });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }
    //ENDBuild roles data

    //Build permissions data
    public function getPermissions(Request $request){

        \DB::statement(\DB::raw('set @rownum=0'));
        $permissions = Permission::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'slug',
            'description'
        ]);
        $datatables = Datatables::of($permissions)
        ->addColumn('actions', function($permissions){
                    $actions_html ='<a href="'.url('permission/'.$permissions->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this role">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';

                    return $actions_html;
            });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }
    //ENDBuild permissions data

    //Function to get vehicle list
    public function getVehicles(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $vehicles = Vehicle::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'category',
            'number_of_vehicle',
        ]);

        $data_vehicles = Datatables::of($vehicles)
            ->editColumn('category', function($vehicles){
                $category = '';
                if($vehicles->category == 'truck'){
                    $category = "Truck";
                }else if($vehicles->category == 'pick_up'){
                    $category = "Pick Up";
                }else if($vehicles->category == 'motorcycle'){
                    $category = "Motorcycle";
                }
                return $category;
            })
            ->addColumn('actions', function($vehicles){
                $actions_html ='<a href="'.url('vehicle/'.$vehicles->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                $actions_html .='</a>&nbsp;';
                $actions_html .='<a href="'.url('vehicle/'.$vehicles->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this vehicle">';
                $actions_html .=    '<i class="fa fa-edit"></i>';
                $actions_html .='</a>&nbsp;';
                if(\Auth::user()->can('delete-vehicle-module'))
                {
                    $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-vehicle" data-id="'.$vehicles->id.'" data-text="'.$vehicles->name.'" title="Click to delete this vehicle">';
                    $actions_html .=    '<i class="fa fa-trash"></i>';
                    $actions_html .='</button>';
                }

                return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_vehicles->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_vehicles->make(true);

    }
    //ENDFunction to get vehicle list

    public function getChartAccounts(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $chart_accounts = ChartAccount::select([
            \DB::raw('@rownum := @rownum + 1 AS rownum'),
            'id',
            'name',
            'account_number',
            'description',
        ]);

        $data_chart_accounts = Datatables::of($chart_accounts)
        ->addColumn('actions', function($chart_accounts){
            $actions_html ='<a href="'.url('chart-account/'.$chart_accounts->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
            $actions_html .=    '<i class="fa fa-external-link-square"></i>';
            $actions_html .='</a>&nbsp;';
            $actions_html .='<a href="'.url('chart-account/'.$chart_accounts->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this chart account">';
            $actions_html .=    '<i class="fa fa-edit"></i>';
            $actions_html .='</a>&nbsp;';
            if(\Auth::user()->can('delete-chart-account-module'))
            {
                $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-chart-account" data-id="'.$chart_accounts->id.'" data-text="'.$chart_accounts->name.'" title="Click to delete this chart account">';
                $actions_html .=    '<i class="fa fa-trash"></i>';
                $actions_html .='</button>';
            }


            return $actions_html;
        });

        if($keyword = $request->get('search')['value']){
            $data_chart_accounts->filterColumn('rownum','whereRaw','@rownum + 1 like ?', ["%{$keyword}"]);
        }

        return $data_chart_accounts->make(true);
    }

    //Function to get sub chart account
    public function getSubChartAccounts(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        $sub_chart_accounts = SubChartAccount::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'account_number',
        ]);
        $datatables = Datatables::of($sub_chart_accounts)
            ->editColumn('chart_account_id',function($sub_chart_accounts){
                return 'hai';
            })
            ->editColumn('account_number_chart_account',function($sub_chart_accounts){
                return 'hai';
            });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);

    }
    //ENDFunction to get product list

    //Function to get transaction
    public function getTransactionChartAccounts(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $trans_chart_account = TransactionChartAccount::select([
            \DB::raw('@rownum := @rownum + 1 AS rownum'),
            'id',
            'sub_chart_account_id',
            'amount',
            'created_at',
            'description',
            'memo',
        ])->where([['source','biaya_operasi'],['type','!=','keluar']]);
        $datatables = Datatables::of($trans_chart_account)
        ->editColumn('id', function($trans_chart_account){
             return 'TS'.$trans_chart_account->id;

        })
        ->editColumn('name', function($trans_chart_account){
            return $trans_chart_account->sub_chart_account->name;
        })
        ->editColumn('amount', function($trans_chart_account){
            return number_format($trans_chart_account->amount);
        })
        ->addColumn('actions', function($trans_chart_account){
            $actions_html ='<a href="'.url('biaya-operasi/'.$trans_chart_account->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
            $actions_html .=    '<i class="fa fa-external-link-square"></i>';
            $actions_html .='</a>&nbsp;';
            $actions_html .='<a href="'.url('biaya-operasi/'.$trans_chart_account->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this jurnal umum">';
            $actions_html .=    '<i class="fa fa-edit"></i>';
            $actions_html .='</a>&nbsp;';
            if(\Auth::user()->can('delete-jurnal-umum-module'))
            {
                $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-trans-chart-account" data-id="'.$trans_chart_account->id.'" data-memo="'.$trans_chart_account->description.'" data-text="'.$trans_chart_account->sub_chart_account->name.'" data-sub-account-id="'.$trans_chart_account->sub_chart_account->chart_account_id.'" title="Click to delete this jurnal umum">';
                $actions_html .=    '<i class="fa fa-trash"></i>';
                $actions_html .='</button>';
            }

            return $actions_html;
        });

        if($keyword = $request->get('search')['value']){
            $trans_chart_account->filterColumn('rownum','whereRaw','@rownum + 1 like ?', ["%{$keyword}"]);
        }

        return $datatables->make(true);
    }

    public function getAssets(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        //\DB::table('suppliers')->orderBy('code','asc')->get();
        $assets = Asset::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'name',
            'date_purchase',
            'amount',
            'periode',
        ]);

        $data_assets = Datatables::of($assets)
            ->editColumn('amount', function($assets){
                    return number_format($assets->amount);
            })
            ->editColumn('periode', function($assets){
                    $thn = $assets->periode/12;
                    return $assets->periode.' Bulan '."($thn Tahun)";
            })
            ->addColumn('actions', function($assets){
                    $actions_html ='<a href="'.url('asset/'.$assets->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('asset/'.$assets->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this driver">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                    if(\Auth::user()->can('delete-asset-module'))
                    {
                      $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-asset" data-id="'.$assets->id.'" data-text="'.$assets->name.'">';
                      $actions_html .=    '<i class="fa fa-trash"></i>';
                      $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_assets->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_assets->make(true);

    }

    public function getAdjustments(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        //\DB::table('suppliers')->orderBy('code','asc')->get();
        $adjustment = Adjustment::with('product_adjustment')->select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'code',
            'created_at',
            'in_out',
            'notes',
            'created_at',
        ]);

        $data_adjustments = Datatables::of($adjustment)
            ->editColumn('qty', function($adjustment){
                    return $adjustment->product_adjustment->sum('qty');
            })
            ->addColumn('actions', function($adjustment){
                    $actions_html ='<a href="'.url('product-adjustment/'.$adjustment->id.'').'" class="btn btn-info btn-xs" title="Click to view the detail">';
                    $actions_html .=    '<i class="fa fa-external-link-square"></i>';
                    $actions_html .='</a>&nbsp;';
                    $actions_html .='<a href="'.url('product-adjustment/'.$adjustment->id.'/edit').'" class="btn btn-success btn-xs" title="Click to edit this adjustment product">';
                    $actions_html .=    '<i class="fa fa-edit"></i>';
                    $actions_html .='</a>&nbsp;';
                     if(\Auth::user()->can('delete-product-adjustment-module'))
                     {
                      $actions_html .='<button type="button" class="btn btn-danger btn-xs btn-delete-product-adjustment" data-id="'.$adjustment->id.'" data-text="'.$adjustment->name.'" title="Click to delete this adjustment product">';
                      $actions_html .=    '<i class="fa fa-trash"></i>';
                      $actions_html .='</button>';
                    }

                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_adjustments->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_adjustments->make(true);

    }

    public function getPurchaseGiros(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        //\DB::table('suppliers')->orderBy('code','asc')->get();
        $giro = GiroPurchaseInvoicePayment::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'no_giro',
            'bank',
            'tanggal_cair',
            'amount',
            'status',
            'created_at',
        ]);

        $data_giros = Datatables::of($giro)
            ->editColumn('status',function($giro){
                if($giro->status == 'pos'){
                    $label = '<span class="label label-warning">'.strtoupper($giro->status).'</span>';
                    return $label;
                }else{
                    $label = '<span class="label label-success">'.strtoupper($giro->status).'</span>';
                    return $label;
                }

            })
            ->addColumn('actions', function($giro){
                $actions_html ='<button type="button" class="btn btn-primary btn-xs btn-approve-giro" data-id="'.$giro->id.'" data-status="'.$giro->status.'" data-text="'.$giro->no_giro.'" title="Click to approve this giro">';
                $actions_html .=    '<i class="fa fa-check"></i> Approve';
                $actions_html .='</button>';
                return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_giros->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_giros->make(true);

    }

    public function getSalesGiros(Request $request){
        \DB::statement(\DB::raw('set @rownum=0'));
        //\DB::table('suppliers')->orderBy('code','asc')->get();
        $giro = GiroSalesInvoicePayment::select([
            \DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'no_giro',
            'bank',
            'tanggal_cair',
            'amount',
            'status',
            'created_at',
        ]);

        $data_giros = Datatables::of($giro)
            ->editColumn('status',function($giro){
                if($giro->status == 'pos'){
                    $label = '<span class="label label-warning">'.strtoupper($giro->status).'</span>';
                    return $label;
                }else{
                    $label = '<span class="label label-success">'.strtoupper($giro->status).'</span>';
                    return $label;
                }

            })
            ->addColumn('actions', function($giro){
                    $actions_html ='<button type="button" class="btn btn-primary btn-xs btn-approve-giro" data-id="'.$giro->id.'" data-status="'.$giro->status.'" data-text="'.$giro->no_giro.'" title="Click to approve this giro">';
                    $actions_html .=    '<i class="fa fa-check"></i> Approve';
                    $actions_html .='</button>';
                    return $actions_html;
            });

        if ($keyword = $request->get('search')['value']) {
            $data_giros->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $data_giros->make(true);

    }
}
