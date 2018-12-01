<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreProductAdjustmentRequest;

use App\SubChartAccount;
use App\Adjustment;
use App\ProductAdjustment;
use App\Product;
use App\MainProduct;

class ProductAdjusmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('product-adjustment-module')){
            return view('adjustment.index');
        }else{
            return view('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create-product-adjustment-module')){
          $adjust_account = SubChartAccount::all();
          $product = Product::all();
          $adjust_product = Adjustment::all();
          $count_adj = '';
          $code_adj = '';
          if(count($adjust_product) == 0)
          {
              $count_adj = count($adjust_product)+1;
              $code_adj = 'ADJ'.$count_adj;
          }else
          {
              $count_adj = Adjustment::all()->max('id');
              $sub_str = $count_adj+1;
              $code_adj = 'ADJ'.$sub_str;
          }
          return view('adjustment.create')
              ->with('adjust_account',$adjust_account)
              ->with('product',$product)
              ->with('code_adj',$code_adj);
        }else{
          return view('403');
        }
    }

    public function callSubProduct(Request $request)
    {
        if($request->ajax()){
            $results = array();
            $list_sub_product = \DB::table('products')->where('main_product_id',$request->id)->get();
            foreach($list_sub_product as $ls){
                array_push($results,[
                    'id'=>$ls->id,
                    'name'=>$ls->name,
                    'family'=>MainProduct::find($request->id)->family->name,
                    'description'=>$ls->description,
                    'stock'=>$ls->stock,
                    'unit'=>MainProduct::find($request->id)->unit->name,
                    'category'=>MainProduct::find($request->id)->category->name,
                    'created_at'=>$ls->created_at,
                ]);
            }
            return response()->json($results);
        }
    }

    // public function callFieldProduct(Request $request)
    // {
    //     if($request->ajax()){
    //         $results = [];
    //         $product = \DB::table('products')->where('id',$request->product)->get();
    //         foreach ($product as $key) {
    //             array_push($results,[
    //                 'description'=>$key->description,
    //                 'unit'=>\DB::table('main_products')->where('id',$key->main_product_id)->value('unit_id'),
    //             ]);
    //         }
    //         return response()->json($results);
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAdjustmentRequest $request)
    {
        $adjustment = New Adjustment;
        $adjustment->code = $request->adjust_no;
        $adjustment->in_out = $request->in_out;
        $adjustment->notes = $request->notes;
        $adjustment->save();

        $data_product_adj = [];
        $product_adjs = New ProductAdjustment;
        foreach ($request->product_id as $key => $value) {
            array_push($data_product_adj,[
                'product_id'=>$request->product_id[$key],
                'unit_cost'=>floatval(preg_replace('#[^0-9.]#', '', $request->unit_cost[$key])),
                'qty'=>floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key])),
                'total'=>floatval(preg_replace('#[^0-9.]#', '', $request->total[$key])),
                'adjustment_id'=>$adjustment->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
        }
        \DB::table('product_adjustment')->insert($data_product_adj);

        $data_potongan_stock = [];
        $sum_potongan = 0;
        if($request->adjust_account == 110)
        {
            if($request->in_out == 'in')
            {
                foreach ($request->product_id as $key => $value) {
                    $stock_first = \DB::table('products')->select('stock')->where('id',$request->product_id[$key])->value('stock');
                    \DB::table('products')->where('id',$request->product_id[$key])->update(['stock'=>$stock_first+floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]))]);
                }
            }elseif ($request->in_out == 'out') {
                foreach ($request->product_id as $key => $value) {
                    $stock_first = \DB::table('products')->select('stock')->where('id',$request->product_id[$key])->value('stock');
                    \DB::table('products')->where('id',$request->product_id[$key])->update(['stock'=>$stock_first-floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]))]);
                    $sum_potongan += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                }
                // array_push($data_potongan_stock,[
                //     'amount'=>$sum_potongan,
                //     'sub_chart_account_id'=>$request->adjust_account,
                //     'created_at'=>date('Y-m-d h:i:s'),
                //     'updated_at'=>date('Y-m-d h:i:s'),
                //     'reference'=>$adjustment->id,
                //     'source'=>'potongan_pembelian',
                //     'type'=>'masuk',
                //     'description'=>'POTONGAN PEMBELIAN',
                //     'memo'=>'POTONGAN PEMBELIAN'
                // ]);
                // \DB::table('transaction_chart_accounts')->insert($data_potongan_stock);
            }
        }elseif ($request->adjust_account == 'initial_setup') {
            //$sum_kain = [];
            $data_kain = [];
            $data_sprei = [];
            $data_selimut = [];
            $data_bedcover = [];
            $data_matras = [];
            $data_plastik = [];
            $sum_kain = '';
            $sum_kain_quantity = '';
            $id_kain = '';
            $sum_sprei = '';
            $sum_sprei_quantity = '';
            $id_sprei = '';
            $sum_selimut = '';
            $sum_selimut_quantity = '';
            $id_selimut = '';
            $sum_bedcover = '';
            $sum_bedcover_quantity = '';
            $id_bedcover = '';
            $sum_matras = '';
            $sum_matras_quantity = '';
            $id_matras = '';
            $sum_plastik = '';
            $sum_plastik_quantity = '';
            $id_plastik = '';
            foreach ($request->product_id as $key => $value) {
                if($request->family_name[$key] == 'KAIN')
                {
                    $sum_kain += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_kain_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_kain = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'SPREI') {
                    $sum_sprei += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_sprei_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_sprei = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'SELIMUT') {
                    $sum_selimut += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_selimut_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_selimut = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'BEDCOVER') {
                    $sum_bedcover += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_bedcover_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_bedcover = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'MATRAS') {
                    $sum_matras += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_matras_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_matras = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'PLASTIK') {
                    $sum_plastik += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_plastik_quantity += floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_plastik = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }

            array_push($data_kain,[
                'amount'=>$sum_kain,
                'sub_chart_account_id'=>$id_kain,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_kain_quantity
            ]);
            array_push($data_sprei,[
                'amount'=>$sum_sprei,
                'sub_chart_account_id'=>$id_sprei,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_sprei_quantity
            ]);
            array_push($data_selimut,[
                'amount'=>$sum_selimut,
                'sub_chart_account_id'=>$id_selimut,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_selimut_quantity
            ]);
            array_push($data_bedcover,[
                'amount'=>$sum_bedcover,
                'sub_chart_account_id'=>$id_bedcover,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_bedcover_quantity
            ]);
            array_push($data_matras,[
                'amount'=>$sum_matras,
                'sub_chart_account_id'=>$id_matras,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_matras_quantity
            ]);
            array_push($data_plastik,[
                'amount'=>$sum_plastik,
                'sub_chart_account_id'=>$id_plastik,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_plastik_quantity
            ]);
                if($sum_kain > 0){
                    \DB::table('transaction_chart_accounts')->insert($data_kain);
                }
                if ($sum_sprei > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_sprei);
                }
                if ($sum_selimut > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_selimut);
                }
                if ($sum_bedcover > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_bedcover);
                }
                if ($sum_matras > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_matras);
                }
                if ($sum_plastik > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_plastik);
                }
        }

        return redirect('product-adjustment')
            ->with('successMessage','Adjustment has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $adjustment = Adjustment::findorFail($id);
        return view('adjustment.show')
            ->with('adjustment',$adjustment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-product-adjustment-module')){
          $adjust_account = SubChartAccount::all();
          $adjustment = Adjustment::findorFail($id);
          $main_product = $adjustment->product_adjustment;

          $row_display = [];
          $main_products_arr = [];
          if($adjustment->product_adjustment->count()){
              foreach($adjustment->product_adjustment as $prod){
                  array_push($main_products_arr, \DB::table('products')->select('main_product_id')->where('id',$prod->product_id)->value('main_product_id'));
              }
          }

          $main_products = array_unique($main_products_arr);

          foreach($main_products as $mp_id){
              $row_display[] = [
                  'main_product_id'=>MainProduct::find($mp_id)->id,
                  'main_product'=>MainProduct::find($mp_id)->name,
                  'image'=>MainProduct::find($mp_id)->image,
                  'description'=>MainProduct::find($mp_id)->product->first()->description,
                  'family'=>MainProduct::find($mp_id)->family->name,
                  'unit'=>MainProduct::find($mp_id)->unit->name,
                  'quantity'=>MainProduct::find($mp_id)->product->sum('stock'),
                  'category'=>MainProduct::find($mp_id)->category->name,
                  'ordered_products'=>$this->get_product_lists($mp_id, $id)
              ];
          }
          return view('adjustment.edit')
              ->with('adjustment',$adjustment)
              ->with('adjust_account',$adjust_account)
              ->with('main_product',$main_product)
              ->with('row_display', $row_display);
        }else{
          return view('403');
        }
    }

    protected function get_product_lists($mp_id, $po_id)
    {

        $product_id_arr = [];
        $product_ids = MainProduct::find($mp_id)->product;
        foreach($product_ids as $pid){
            $counter = \DB::table('product_adjustment')
                        ->where('product_id','=', $pid->id)
                        ->where('adjustment_id', '=', $po_id)
                        ->first();
            if(count($counter)){
                array_push($product_id_arr,array(
                    'family'=>Product::findOrFail($pid->id)->main_product->family->name,
                    'code'=>Product::findOrFail($pid->id)->name,
                    'description'=>Product::findOrFail($pid->id)->description,
                    'unit'=>Product::findOrFail($pid->id)->main_product->unit->name,
                    'quantity'=>$counter->qty,
                    'product_id'=>$counter->product_id,
                    'category'=>Product::findOrFail($pid->id)->main_product->category->name,
                ));
            }
            //$product_id_arr[] = $pid->id;
        }
        return $product_id_arr;


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $adjustment = Adjustment::findorFail($request->adjustment_id);
        $adjustment->code = $request->code;
        $adjustment->in_out = $request->in_out;
        $adjustment->notes = $request->notes;
        $adjustment->save();

        \DB::table('product_adjustment')->where('adjustment_id',$request->adjustment_id)->delete();
        $data_product_adj = [];
        $product_adjs = New ProductAdjustment;
        foreach ($request->product_id as $key => $value) {
            array_push($data_product_adj,[
                'product_id'=>$request->product_id[$key],
                'unit_cost'=>floatval(preg_replace('#[^0-9.]#', '', $request->unit_cost[$key])),
                'qty'=>$request->quantity[$key],
                'total'=>floatval(preg_replace('#[^0-9.]#', '', $request->total[$key])),
                'adjustment_id'=>$adjustment->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
        }
        \DB::table('product_adjustment')->insert($data_product_adj);

        $data_potongan_stock = [];
        $sum_potongan = 0;
        if($request->adjust_account == 110)
        {
            if($request->in_out == 'in')
            {
                foreach ($request->product_id as $key => $value) {
                    $stock_first = \DB::table('products')->select('stock')->where('id',$request->product_id[$key])->value('stock');
                    \DB::table('products')->where('id',$request->product_id[$key])->update(['stock'=>$stock_first+$request->quantity[$key]]);
                }
            }elseif ($request->in_out == 'out') {
                foreach ($request->product_id as $key => $value) {
                    $stock_first = \DB::table('products')->select('stock')->where('id',$request->product_id[$key])->value('stock');
                    \DB::table('products')->where('id',$request->product_id[$key])->update(['stock'=>$stock_first-$request->quantity[$key]]);
                    $sum_potongan += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                }
                array_push($data_potongan_stock,[
                    'amount'=>$sum_potongan,
                    'sub_chart_account_id'=>$request->adjust_account,
                    'created_at'=>date('Y-m-d h:i:s'),
                    'updated_at'=>date('Y-m-d h:i:s'),
                    'reference'=>$adjustment->id,
                    'source'=>'potongan_pembelian',
                    'type'=>'masuk',
                    'description'=>'POTONGAN PEMBELIAN',
                    'memo'=>'POTONGAN PEMBELIAN'
                ]);
                \DB::table('transaction_chart_accounts')->insert($data_potongan_stock);

            }
        }elseif ($request->adjust_account == 'initial_setup') {
            \DB::table('transaction_chart_accounts')->where('reference',$adjustment->id)->where('source','initial_setup')->delete();
            $data_kain = [];
            $data_sprei = [];
            $data_selimut = [];
            $data_bedcover = [];
            $data_matras = [];
            $data_plastik = [];
            $sum_kain = '';
            $sum_kain_quantity = '';
            $id_kain = '';
            $sum_sprei = '';
            $sum_sprei_quantity = '';
            $id_sprei = '';
            $sum_selimut = '';
            $sum_selimut_quantity = '';
            $id_selimut = '';
            $sum_bedcover = '';
            $sum_bedcover_quantity = '';
            $id_bedcover = '';
            $sum_matras = '';
            $sum_matras_quantity = '';
            $id_matras = '';
            $sum_plastik = '';
            $sum_plastik_quantity = '';
            $id_plastik = '';
            foreach ($request->product_id as $key => $value) {
                if($request->family_name[$key] == 'KAIN')
                {
                    $sum_kain += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_kain_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_kain = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'SPREI') {
                    $sum_sprei += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_sprei_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_sprei = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'SELIMUT') {
                    $sum_selimut += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_selimut_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_selimut = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'BEDCOVER') {
                    $sum_bedcover += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_bedcover_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_bedcover = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'MATRAS') {
                    $sum_matras += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_matras_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_matras = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }
            foreach ($request->product_id as $key => $value) {
                if ($request->family_name[$key] == 'PLASTIK') {
                    $sum_plastik += floatval(preg_replace('#[^0-9.]#', '', $request->total[$key]));
                    $sum_plastik_quantity = floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]));
                    $id_plastik = \DB::table('sub_chart_accounts')->select('id')->where('name','PERSEDIAAN '.$request->family_name[$key])->value('id');
                }
            }

            array_push($data_kain,[
                'amount'=>$sum_kain,
                'sub_chart_account_id'=>$id_kain,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_kain_quantity
            ]);
            array_push($data_sprei,[
                'amount'=>$sum_sprei,
                'sub_chart_account_id'=>$id_sprei,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_sprei_quantity
            ]);
            array_push($data_selimut,[
                'amount'=>$sum_selimut,
                'sub_chart_account_id'=>$id_selimut,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_selimut_quantity
            ]);
            array_push($data_bedcover,[
                'amount'=>$sum_bedcover,
                'sub_chart_account_id'=>$id_bedcover,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_bedcover_quantity
            ]);
            array_push($data_matras,[
                'amount'=>$sum_matras,
                'sub_chart_account_id'=>$id_matras,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_matras_quantity
            ]);
            array_push($data_plastik,[
                'amount'=>$sum_plastik,
                'sub_chart_account_id'=>$id_plastik,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$adjustment->id,
                'source'=>'initial_setup',
                'type'=>'masuk',
                'description'=>'SALDO AWAL',
                'memo'=>$sum_plastik_quantity
            ]);
                if($sum_kain > 0){
                    \DB::table('transaction_chart_accounts')->insert($data_kain);
                }
                if ($sum_sprei > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_sprei);
                }
                if ($sum_selimut > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_selimut);
                }
                if ($sum_bedcover > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_bedcover);
                }
                if ($sum_matras > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_matras);
                }
                if ($sum_plastik > 0) {
                    \DB::table('transaction_chart_accounts')->insert($data_plastik);
                }
        }

        return redirect('product-adjustment')
            ->with('successMessage','Adjustment has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $adjustment = Adjustment::findorFail($request->adjustment_id);
        $adjustment->delete();

        \DB::table('product_adjustment')->where('adjustment_id',$request->adjustment_id)->delete();
        \DB::table('transaction_chart_accounts')->where('reference',$request->adjustment_id)->where('source','initial_setup')->where('description','SALDO AWAL')->delete();
        return redirect('product-adjustment')
            ->with('successMessage','Adjustment has been deleted');
    }
}
