<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ChartAccount;

use App\Helpers\Helpers;

class LostProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('lost-profit-module'))
        {
            $chart_account = \DB::table('chart_accounts')->get();
            $sub_chart_account = \DB::table('sub_chart_accounts')->get();
            return view('lost_profit.list')
                    ->with('chart_account',$chart_account);
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

    public function lost_profit_sort_submit(Request $request)
    {
        $sort_by_year = $request->sort_by_year;
        if($sort_by_year == 'y'){
            true;
            $year = $request->years;
            $year_in = 'y';
            if(true){
                $chart_account = \DB::table('chart_accounts')->get();
                return view('lost_profit.list')
                    ->with('chart_account',$chart_account)
                    ->with('year',$year)
                    ->with('year_in',$year_in);
            }// }else{
            //     $chart_account = \DB::table('chart_accounts')->get();
            //     return view('neraca.list')
            //         ->with('chart_account',$chart_account);
            // }
        }elseif ($sort_by_year == 'm') {
            true;
            $month_start = $request->list_months_start;
            $year_start = $request->list_years_start;
            $month_end = $request->list_months_end;
            $year_end = $request->list_years_end;
            $month_in = 'm';
            $conv_month_start ='';
            $conv_month_end ='';
            switch ($month_start) {
                case 01:
                    $conv_month_start = 'January';
                    break;
                case 02:
                    $conv_month_start = 'February';
                    break;
                case 03:
                    $conv_month_start = 'March';
                    break;
                case 04:
                    $conv_month_start = 'April';
                    break;
                case 05:
                    $conv_month_start = 'May';
                    break;
                case 06:
                    $conv_month_start = 'June';
                    break;
                case 07:
                    $conv_month_start = 'July';
                    break;
                case 08:
                    $conv_month_start = 'August';
                    break;
                case 09:
                    $conv_month_start = 'September';
                    break;
                case 10:
                    $conv_month_start = 'October';
                    break;
                case 11:
                    $conv_month_start = 'November';
                    break;
                case 12:
                    $conv_month_start = 'December';
                    break;
                default:
                    # code...
                    break;
            }
            switch ($month_end) {
                case 01:
                    $conv_month_end = 'January';
                    break;
                case 02:
                    $conv_month_end = 'February';
                    break;
                case 03:
                    $conv_month_end = 'March';
                    break;
                case 04:
                    $conv_month_end = 'April';
                    break;
                case 05:
                    $conv_month_end = 'May';
                    break;
                case 06:
                    $conv_month_end = 'June';
                    break;
                case 07:
                    $conv_month_end = 'July';
                    break;
                case 08:
                    $conv_month_end = 'August';
                    break;
                case 09:
                    $conv_month_end = 'September';
                    break;
                case 10:
                    $conv_month_end = 'October';
                    break;
                case 11:
                    $conv_month_end = 'November';
                    break;
                case 12:
                    $conv_month_end = 'December';
                    break;
                default:
                    # code...
                    break;
            }
            if(true){
                $chart_account = \DB::table('chart_accounts')->get();
                return view('lost_profit.list')
                    ->with('chart_account',$chart_account)
                    ->with('month_start',$month_start)
                    ->with('year_start',$year_start)
                    ->with('conv_month_start',$conv_month_start)
                    ->with('conv_month_end',$conv_month_end)
                    ->with('month_end',$month_end)
                    ->with('year_end',$year_end)
                    ->with('month_in',$month_in);
            }
        }else{
            $chart_account = \DB::table('chart_accounts')->get();
            return view('lost_profit.list')
                ->with('chart_account',$chart_account);
        }
    }

    public function lost_profit_print(Request $request)
    {
        if(\Auth::user()->can('print-lost-profit-module'))
        {
            $sort_target = $request->sort_target;
            if($sort_target == 'y'){
                true;
                $data['sort_target_year'] = $request->sort_target_year;
                $data['sort_target_y'] = 'y';
                if(true){
                    $data['chart_account'] = ChartAccount::all();

                    $pdf = \PDF::loadView('pdf.lost_profit_montly',$data);
                    return $pdf->stream('lost_profit_montly.pdf');
                }
            }elseif ($sort_target == 'm') {
                true;
                $data['month_start'] = $request->sort_target_months_start;
                $data['year_start'] = $request->sort_target_years_start;
                $data['month_end'] = $request->sort_target_months_end;
                $data['year_end'] = $request->sort_target_years_end;
                $data['sort_target_m'] = 'm';
                $conv_month_start ='';
                $conv_month_end ='';
                if(true){
                    $data['chart_account'] = ChartAccount::all();

                    $pdf = \PDF::loadView('pdf.lost_profit_montly',$data);
                    return $pdf->stream('lost_profit_montly.pdf');
                }
            }
        }else{
            return view('403');
        }
    }
}
