<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SubChartAccount;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('cash-flow-module'))
      {
        return view('cash_flow.index');
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

    public function cash_flow_search(Request $request)
    {
        $chart_account = \DB::table('chart_accounts')->get();
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        return view('cash_flow.index')
            ->with('date_start',$date_start)
            ->with('date_end',$date_end)
            ->with('chart_account',$chart_account);
    }

    public function cash_flow_print(Request $request)
    {
      if(\Auth::user()->can('print-cash-flow-module'))
      {
        $data = [
                'date_start'=>$request->sort_date_start,
                'date_end'=>$request->sort_date_end,
                'lost_profit'=>$request->sort_lost_profit,
                'akumulasi_penyusutan'=>$request->sort_akumulasi_penyusutan,
                'akun_hutang'=>$request->sort_akun_hutang,
                'kewajiban_lancar_lainnya'=>$request->sort_kewajiban_lancar_lainnya,
                'akun_piutang'=>$request->sort_akun_piutang,
                'aset_lancar_lainnya'=>$request->sort_aset_lancar_lainnya,
                'nilai_histori'=>$request->sort_nilai_histori,
                'kewajiban_jangka_panjang'=>$request->sort_kewajiban_jangka_panjang,
                'ekuitas'=>$request->sort_ekuitas,
        ];
        $pdf = \PDF::loadView('pdf.cash_flow',$data);
        return $pdf->stream('cash_flow.pdf');
      }else{
        return view('403');
      }
    }
}
