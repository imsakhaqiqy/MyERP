<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


//Form requests
use App\Http\Requests\StoreStockBalance;
use App\Http\Requests\UpdateStockBalanceRequest;
// Use Modal
use App\StockBalance;
use App\Product;
use DB;

class StockBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('stock-balance-module'))
        {
            return view('stock_balance.index');
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
        if(\Auth::user()->can('create-stock-balance-module'))
        {
            $stock_balance = StockBalance::all();
            $code_fix = '';
            if(count($stock_balance) > 0)
            {
                $code_stock_balance = StockBalance::all()->max('id');
                $sub_str = $code_stock_balance+1;
                $code_fix = 'SB0'.$sub_str;
            }else
            {
                $code_stock_balance = count($stock_balance)+1;
                $code_fix = 'SB0'.$code_stock_balance;
            }
            $data = Product::get();
            return view('stock_balance.create')
                ->with('dataList',$data)
                ->with('code_fix',$code_fix);
        }else{
            return view('403');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockBalance $request)
    {
        $data = array(
            'code' => $request->code,
            'created_by' => \Auth::user()->id
        );
        $save = StockBalance::create($data);

        $stock_balance_id = $save->id;

        $stock_balance = StockBalance::find($stock_balance_id);

        //Build sync data to store the relation w/ products
        $syncData = [];
        foreach($request->product_id as $key=>$value){
            //$syncData[$value] = ['quantity'=> $request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
            $syncData[$value] = [
                                'system_stock'=> $request->system_stock[$key],
                                'real_stock' => $request->real_stock[$key],
                                'information' => $request->information[$key]
                                ];
        }
        //sync the purchase order product relation
        $stock_balance->products()->sync($syncData);
        return redirect('stock_balance')
            ->with('successMessage','Stock balance has been added');
        // print_r($request->real_stock);
        // print_r($request->product_id);~
        // print_r($request->system_stock);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \DB::table('product_stock_balance')
        ->join('products','product_stock_balance.product_id','=','products.id')
        ->join('main_products','products.main_product_id','=','main_products.id')
        ->select('product_stock_balance.*','products.name','products.description','main_products.*')
        ->where('product_stock_balance.stock_balance_id','=',$id)
        ->get();
        //$data->product_stock_balance()->get();
        $stock_balance = StockBalance::findOrFail($id);
        return view('stock_balance.show')
            ->with('dataList',$data)
            ->with('stock_balance',$stock_balance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-stock-balance-module'))
        {
            $data = \DB::table('product_stock_balance')
            ->join('products','product_stock_balance.product_id','=','products.id')
            ->join('main_products','products.main_product_id','=','main_products.id')
            ->select('product_stock_balance.*','products.name','products.description','main_products.*')
            ->where('product_stock_balance.stock_balance_id','=',$id)
            ->get();
            $stock_balance = StockBalance::findOrFail($id);
            return view('stock_balance.edit')
                ->with('dataList',$data)
                ->with('stock_balance',$stock_balance);
        }else{
            return view('403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockBalanceRequest $request, $id)
    {
        // $data = \DB::table('stock_balance')
        // ->join('product_stock_balance','product_stock_balance.stock_balance_id','=','stock_balance.id')
        // ->where('stock_balance.id','=',$id)
        // ->update('');
        $stock_balance = StockBalance::findOrFail($id);
        $stock_balance->code = $request->code;
        $stock_balance->created_by = $request->created_by;
        $stock_balance->save();
        \DB::table('product_stock_balance')->where('stock_balance_id','=',$request->id)->delete();
        $syncData = [];
        foreach($request->product_id as $key=>$value){
            //$syncData[$value] = ['quantity'=> $request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
            $syncData[$value] = [
                                'stock_balance_id' => $request->stock_balance_id[$key],
                                'product_id' => $request->product_id[$key],
                                'system_stock' => $request->system_stock[$key],
                                'real_stock' => $request->real_stock[$key],
                                'information' => $request->information[$key]
                                ];
        }
        //sync the purchase order product relation
        \DB::table('product_stock_balance')->insert($syncData);
        return redirect('stock_balance')
            ->with('successMessage',"$stock_balance->code has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $stock_balance = StockBalance::findOrFail($request->stock_balance_id);
        $stock_balance->delete();
        \DB::table('product_stock_balance')->where('stock_balance_id','=',$request->stock_balance_id)->delete();
        return redirect('stock_balance')
            ->with('successMessage',"$stock_balance->code has been deleted");
    }

    public function printStockBalance(Request $request)
    {
        $data['products'] = Product::get();
        $data['tgl'] = date('Y-m-d h:i:s');
        $pdf = \PDF::loadView('pdf.stock_balance',$data);
        return $pdf->stream('stock_balance.pdf');
    }

    public function printPdf(Request $request)
    {
      $data['products'] = \DB::table('product_stock_balance')
      ->join('products','product_stock_balance.product_id','=','products.id')
      ->join('main_products','products.main_product_id','=','main_products.id')
      ->select('product_stock_balance.*','products.name','products.description','main_products.*')
      ->where('product_stock_balance.stock_balance_id','=',$request->id)
      ->get();
      $data['stock_balance'] = StockBalance::findOrFail($request->id);
      $pdf = \PDF::loadView('pdf.show_stock_balance',$data);
      return $pdf->stream('show_stock_balance.pdf');
    }

}
