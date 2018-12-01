<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreChartAccountRequest;
use App\Http\Requests\UpdateChartAccountRequest;
use App\Http\Requests\StoreSubChartAccountRequest;
use App\Http\Requests\UpdateSubChartAccountRequest;
use App\ChartAccount;
use App\SubChartAccount;
use App\TransactionChartAccount;
use App\Cash;
use App\Bank;

class ChartAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('chart-account-module'))
        {
            return view('chart_account.index');
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
        if(\Auth::user()->can('create-chart-account-module'))
        {
            return view('chart_account.create');
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
    public function store(StoreChartAccountRequest $request)
    {
        $chart_account = new ChartAccount;
        $chart_account->name = $request->name;
        $chart_account->account_number = $request->account_number;
        $chart_account->description = $request->description;
        $chart_account->save();
        return redirect('chart-account')
            ->with('successMessage','Chart Account has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chart_account = ChartAccount::findOrFail($id);
        $sub_chart_account = $chart_account->sub_chart_account;
        $list_parent = SubChartAccount::lists('name','id');
        return view('chart_account.show')
                ->with('chart_account',$chart_account)
                ->with('sub_chart_account',$sub_chart_account)
                ->with('list_parent',$list_parent);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-chart-account-module'))
        {
            $chart_account = ChartAccount::findOrFail($id);
            return view('chart_account.edit')
                ->with('chart_account',$chart_account);
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
    public function update(UpdateChartAccountRequest $request, $id)
    {
        $chart_account = ChartAccount::findOrFail($id);
        $chart_account->name = $request->name;
        $chart_account->account_number = $request->account_number;
        $chart_account->description = $request->description;
        $chart_account->save();
        return redirect('chart-account')
            ->with('successMessage',"$chart_account->name has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $chart_account = ChartAccount::findOrFail($request->chart_account_id);
        $chart_account->delete();
        \DB::table('sub_chart_accounts')->where('chart_account_id',$request->chart_account_id)->delete();
        return redirect('chart-account')
            ->with('successMessage',"$chart_account->name has been deleted");
    }


    public function store_sub(StoreSubChartAccountRequest $request)
    {
      if(\Auth::user()->can('create-chart-account-module'))
      {
        $sub_chart_account = New SubChartAccount;
        $sub_chart_account->name = $request->name;
        $sub_chart_account->account_number = $request->account_number;
        $sub_chart_account->chart_account_id = $request->chart_account_id;
        $sub_chart_account->level = $request->level;
        if($request->parent_id != ''){
            $sub_chart_account->parent_id = $request->parent_id;
        }
        $sub_chart_account->saldo_awal = floatval(preg_replace('#[^0-9.]#', '', $request->saldo_awal));
        $sub_chart_account->save();

        $id_sub_chart_account = $sub_chart_account->id;

        $trans_chart_account = New TransactionChartAccount;
        $trans_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->saldo_awal));
        $trans_chart_account->sub_chart_account_id = $id_sub_chart_account;
        $trans_chart_account->created_at = date('Y-m-d H:i:s');
        $trans_chart_account->updated_at = date('Y-m-d H:i:s');
        $trans_chart_account->reference = $id_sub_chart_account;
        $trans_chart_account->source = 'create_sub_chart_account';
        $trans_chart_account->type = 'masuk';
        $trans_chart_account->description = 'SALDO AWAL';
        $trans_chart_account->memo = 'SALDO AWAL';
        $trans_chart_account->save();
        // TODO condition account number

        return redirect('chart-account/'.$request->chart_account_id)
            ->with('successMessage','Sub chart account has been added');
      }else{
        return view('403');
      }

    }

    public function update_sub(Request $request)
    {
      if(\Auth::user()->can('edit-chart-account-module'))
      {
        $sub_chart_account = SubChartAccount::findOrFail($request->sub_chart_account_id);
        $sub_chart_account->name = $request->name;
        $sub_chart_account->account_number = $request->account_number;
        $sub_chart_account->level = $request->level;
        if($request->parent_id != ''){
            $sub_chart_account->parent_id = $request->parent_id;
        }
        $sub_chart_account->saldo_awal = floatval(preg_replace('#[^0-9.]#', '', $request->saldo_awal_edit));
        $sub_chart_account->save();

        $cek_saldo_awal = \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->sub_chart_account_id)->where('description','SALDO AWAL')->where('memo','SALDO AWAL')->value('amount');
        if(count($cek_saldo_awal) > 0)
        {
          \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->sub_chart_account_id)->where('description','SALDO AWAL')->where('memo','SALDO AWAL')->update(['amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->saldo_awal_edit))]);
        }else
        {
          $trans_chart_account = New TransactionChartAccount;
          $trans_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->saldo_awal_edit));
          $trans_chart_account->sub_chart_account_id = $request->sub_chart_account_id;
          $trans_chart_account->created_at = date('Y-m-d H:i:s');
          $trans_chart_account->updated_at = date('Y-m-d H:i:s');
          $trans_chart_account->reference = $request->sub_chart_account_id;
          $trans_chart_account->source = 'create_sub_chart_account';
          $trans_chart_account->type = 'masuk';
          $trans_chart_account->description = 'SALDO AWAL';
          $trans_chart_account->memo = 'SALDO AWAL';
          $trans_chart_account->save();
        }
        return redirect('chart-account/'.$request->chart_account_id)
            ->with('successMessage',"Sub chart account has been updated");
      }else{
        return view('403');
      }

    }

    public function delete_sub(Request $request)
    {
        $sub_chart_account = SubChartAccount::findOrFail($request->sub_chart_account_id);
        $sub_chart_account->delete();
        return redirect('chart-account/'.$request->chart_account_id)
            ->with('successMessage',"Sub chart account has been deleted");
    }

    public function store_saldo_awal(Request $request)
    {
        $trans_chart_account = New TransactionChartAccount;
        $trans_chart_account->amount = $request->saldo_awal;
        $trans_chart_account->sub_chart_account_id = $request->sub_chart_account_id_view;
        $trans_chart_account->created_at = date('Y-m-d H:i:s');
        $trans_chart_account->updated_at = date('Y-m-d H:i:s');
        $trans_chart_account->reference = $request->sub_chart_account_id_view;
        $trans_chart_account->source = 'show_chart_account';
        $trans_chart_account->type = 'masuk';
        $trans_chart_account->description = 'SALDO AWAL';
        $trans_chart_account->memo = 'SALDO AWAL';
        $trans_chart_account->save();
        return redirect('chart-account/'.$request->chart_account_id)
            ->with('successMessage','Saldo awal has added'.$request->sub_chart_account_id_view);
    }
}
