<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SubChartAccount;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('ledger-module'))
      {
        $account = SubChartAccount::all();
        return view('ledger.index')
            ->with('account',$account);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ledger_search(Request $request)
    {
        $account = SubChartAccount::all();
        $query = SubChartAccount::findOrfail($request->account);
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $query_trans = \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$query->id)->whereBetween('created_at',[$date_start.' 00:00:01',$date_end.' 23:59:59'])->get();
        return view('ledger.index')
            ->with('account',$account)
            ->with('query_trans',$query_trans)
            ->with('query_select',$query)
            ->with('date_start',$date_start)
            ->with('date_end',$date_end);
    }

    public function ledger_print(Request $request)
    {
      if(\Auth::user()->can('print-ledger-module'))
      {
        $id_account = $request->sort_target_account;
        $date_start = $request->sort_target_date_start;
        $date_end = $request->sort_target_date_end;
        $data['query_trans'] = \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$id_account)->whereBetween('created_at',[$date_start.' 00:00:01',$date_end.' 23:59:59'])->get();
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;
        $data['sub_account_name'] = SubChartAccount::findOrfail($id_account);
        $pdf = \PDF::loadView('pdf.ledger',$data);
        return $pdf->stream('ledger.pdf');
      }else{
        return view('403');
      }
    }

}
