<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreBiayaOperasiRequest;
use App\Http\Requests\UpdateBiayaOperasiRequest;
use App\TransactionChartAccount;

use App\Cash;
use App\Bank;
use App\SubChartAccount;

class BiayaOperasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('jurnal-umum-module'))
        {
            return view('biaya_operasi.index');
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
        if(\Auth::user()->can('create-jurnal-umum-module'))
        {
            $sub_account = SubChartAccount::all();
            $banks = Bank::lists('name', 'id');
            $cashs = Cash::lists('name','id');
            return view('biaya_operasi.create')
                ->with('cashs',$cashs)
                ->with('banks',$banks)
                ->with('sub_account',$sub_account);
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
    public function store(StoreBiayaOperasiRequest $request)
    {
        $sub_account_id = \DB::table('sub_chart_accounts')->select('chart_account_id')->where('id',$request->beban_operasi_account)->value('chart_account_id');

        //save to cash or bank master
        if($request->pay_method == 2)
        {
          if($sub_account_id == 62)
          {
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first+floatval(preg_replace('#[^0-9.]#', '', $request->debit));
            \DB::table('cashs')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }else{
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first-floatval(preg_replace('#[^0-9.]#', '', $request->debit));
            \DB::table('cashs')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }
        }elseif ($request->pay_method == 1)
        {
          if($sub_account_id == 62)
          {
            $value_first = \DB::table('banks')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first+floatval(preg_replace('#[^0-9.]#', '', $request->debit));
            \DB::table('banks')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }else{
            $value_first = \DB::table('banks')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first-floatval(preg_replace('#[^0-9.]#', '', $request->debit));
            \DB::table('banks')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }
        }else
        {

        }

        //$id_sub_account = \DB::table('sub_chart_accounts')->select('id')->where('name',$request->beban_operasi_account)->value('id');
        // now save beban operasi account
        $trans_chart_account = New TransactionChartAccount;
        $trans_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->debit));
        $trans_chart_account->sub_chart_account_id = $request->beban_operasi_account;
        $trans_chart_account->created_at = date('Y-m-d h:i:s');
        $trans_chart_account->updated_at = date('Y-m-d h:i:s');
        $trans_chart_account->reference = $request->beban_operasi_account;
        $trans_chart_account->source = 'biaya_operasi';
        $trans_chart_account->type = 'masuk';
        $trans_chart_account->description = $request->memo;
        $trans_chart_account->memo = 'BIAYA OPERASIONAL';
        $trans_chart_account->save();

        $id_first_row = $trans_chart_account->id;
        // now save cash/bank account
        if($sub_account_id == 62)
        {
          $trans_chart_account_cb = New TransactionChartAccount;
          $trans_chart_account_cb->amount = floatval(preg_replace('#[^0-9.]#', '', $request->debit));
          $trans_chart_account_cb->sub_chart_account_id = $request->cash_bank_account;
          $trans_chart_account_cb->created_at = date('Y-m-d h:i:s');
          $trans_chart_account_cb->updated_at = date('Y-m-d h:i:s');
          $trans_chart_account_cb->reference = $id_first_row;
          $trans_chart_account_cb->source = $request->cash_or_bank;
          $trans_chart_account_cb->type = 'masuk';
          $trans_chart_account_cb->description = $request->pay_method;
          $trans_chart_account_cb->memo = $request->memo;
          $trans_chart_account_cb->save();
        }else{
          $trans_chart_account_cb = New TransactionChartAccount;
          $trans_chart_account_cb->amount = floatval(preg_replace('#[^0-9.]#', '', $request->debit));
          $trans_chart_account_cb->sub_chart_account_id = $request->cash_bank_account;
          $trans_chart_account_cb->created_at = date('Y-m-d h:i:s');
          $trans_chart_account_cb->updated_at = date('Y-m-d h:i:s');
          $trans_chart_account_cb->reference = $id_first_row;
          $trans_chart_account_cb->source = $request->cash_or_bank;
          $trans_chart_account_cb->type = 'keluar';
          $trans_chart_account_cb->description = $request->pay_method;
          $trans_chart_account_cb->memo = $request->memo;
          $trans_chart_account_cb->save();
        }

        return redirect('biaya-operasi')
            ->with('successMessage','Jurnal Umum has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trans_chart_account = TransactionChartAccount::findOrFail($id);
        return view('biaya_operasi.show_new')
            ->with('trans_chart_account',$trans_chart_account);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-jurnal-umum-module'))
        {
            $trans_chart_account = TransactionChartAccount::findOrFail($id);
            $cash_bank_account = TransactionChartAccount::select('sub_chart_account_id')->where('reference',$id)->get();
            $description = TransactionChartAccount::select('description')->where('reference',$id)->value('description');
            $source = TransactionChartAccount::select('source')->where('reference',$id)->value('source');
            $sub = '';
            $sub_account = SubChartAccount::all();
            $banks = Bank::all();
            $cashs = Cash::all();
            foreach ($cash_bank_account as $key) {
                $sub = $key->sub_chart_account_id;
            }
            $sub_chart_name = SubChartAccount::findOrFail($sub);
            return view('biaya_operasi.edit_new')
                ->with('trans_chart_account',$trans_chart_account)
                ->with('cash_bank_account',$sub)
                ->with('cash',$cashs)
                ->with('bank',$banks)
                ->with('sub_account',$sub_account)
                ->with('description',$description)
                ->with('source',$source);
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
    public function update(Request $request, $id)
    {
      $sub_account_id = \DB::table('sub_chart_accounts')->select('chart_account_id')->where('id',$request->id_sub_account_first)->value('chart_account_id');
        if($request->cash_bank == 2)
        {
          if ($sub_account_id == 62)
          {
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->source)->value('value');
            $back_value = $value_first-floatval(preg_replace('#[^0-9.]#','',$request->amount_first));
            \DB::table('cashs')->where('id',$request->source)->update(['value'=>$back_value]);
          }else{
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->source)->value('value');
            $back_value = $value_first+floatval(preg_replace('#[^0-9.]#','',$request->amount_first));
            \DB::table('cashs')->where('id',$request->source)->update(['value'=>$back_value]);
          }
        }elseif ($request->cash_bank == 1)
        {
          if($sub_account_id == 62)
          {
            $value_first = \DB::table('banks')->select('value')->where('id',$request->source)->value('value');
            $back_value = $value_first-floatval(preg_replace('#[^0-9.]#','',$request->amount_first));
            \DB::table('banks')->where('id',$request->source)->update(['value'=>$back_value]);
          }else{
            $value_first = \DB::table('banks')->select('value')->where('id',$request->source)->value('value');
            $back_value = $value_first+floatval(preg_replace('#[^0-9.]#','',$request->amount_first));
            \DB::table('banks')->where('id',$request->source)->update(['value'=>$back_value]);
          }
        }else
        {

        }

        $sub_account_id_pay = \DB::table('sub_chart_accounts')->select('chart_account_id')->where('id',$request->beban_operasi_account)->value('chart_account_id');

        if($request->pay_method == 2)
        {
          if($sub_account_id_pay == 62)
          {
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first+floatval(preg_replace('#[^0-9.]#','',$request->amount));
            \DB::table('cashs')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }else{
            $value_first = \DB::table('cashs')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $value_first-floatval(preg_replace('#[^0-9.]#','',$request->amount));
            \DB::table('cashs')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }
        }elseif ($request->pay_method == 1)
        {
          if($sub_account_id_pay == 62)
          {
            $value_first = \DB::table('banks')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $back_value+floatval(preg_replace('#[^0-9.]#','',$request->amount));
            \DB::table('banks')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }else{
            $value_first = \DB::table('banks')->select('value')->where('id',$request->cash_or_bank)->value('value');
            $new_value = $back_value-floatval(preg_replace('#[^0-9.]#','',$request->amount));
            \DB::table('banks')->where('id',$request->cash_or_bank)->update(['value'=>$new_value]);
          }
        }else
        {

        }

        // now save beban operasi account
        $trans_chart_account = TransactionChartAccount::findOrFail($id);
        $trans_chart_account->amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));
        $trans_chart_account->sub_chart_account_id = $request->beban_operasi_account;
        $trans_chart_account->created_at = date('Y-m-d h:i:s');
        $trans_chart_account->updated_at = date('Y-m-d h:i:s');
        $trans_chart_account->reference = $request->beban_operasi_account;
        $trans_chart_account->source = 'biaya_operasi';
        $trans_chart_account->type = 'masuk';
        $trans_chart_account->description = $request->memo;
        $trans_chart_account->memo = 'BIAYA OPERASIONAL';
        $trans_chart_account->save();

        if($sub_account_id_pay == 62)
        {
          // now save cash/bank account
          \DB::table('transaction_chart_accounts')->where('reference',$id)->update([
                  'amount'=>floatval(preg_replace('#[^0-9.]#','',$request->amount)),
                  'sub_chart_account_id'=>$request->cash_bank_account,
                  'created_at'=>date('Y-m-d h:i:s'),
                  'updated_at'=>date('Y-m-d h:i:s'),
                  'reference'=>$id,
                  'source'=>$request->cash_or_bank,
                  'type'=>'masuk',
                  'description'=>$request->pay_method,
                  'memo'=>$request->memo
          ]);
        }else{
          // now save cash/bank account
          \DB::table('transaction_chart_accounts')->where('reference',$id)->update([
                  'amount'=>floatval(preg_replace('#[^0-9.]#','',$request->amount)),
                  'sub_chart_account_id'=>$request->cash_bank_account,
                  'created_at'=>date('Y-m-d h:i:s'),
                  'updated_at'=>date('Y-m-d h:i:s'),
                  'reference'=>$id,
                  'source'=>$request->cash_or_bank,
                  'type'=>'keluar',
                  'description'=>$request->pay_method,
                  'memo'=>$request->memo
          ]);
        }

        return redirect('biaya-operasi')
            ->with('successMessage','Jurnal Umum has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $trans_chart_account = TransactionChartAccount::findOrFail($request->trans_id);
        $trans_chart_account->delete();
        //get payment method
        $pay_method = \DB::table('transaction_chart_accounts')->select('description')->where('reference',$request->trans_id)->where('memo',$request->trans_memo)->value('description');
        if($pay_method == 2)
        {
            if($request->trans_chart_account_id == 62)
            {
                //get bank or cash id
                $cash_bank_id = \DB::table('transaction_chart_accounts')->select('source')->where('reference',$request->trans_id)->where('type','masuk')->where('memo',$request->trans_memo)->value('source');
                //get amount trans
                $amount_first = \DB::table('transaction_chart_accounts')->select('amount')->where('reference',$request->trans_id)->where('type','masuk')->where('memo',$request->trans_memo)->value('amount');
                $value_first = \DB::table('cashs')->select('value')->where('id',$cash_bank_id)->value('value');
                $back_value = $value_first-$amount_first;
                \DB::table('cashs')->where('id',$cash_bank_id)->update(['value'=>$back_value]);
                // print_r($back_value);
                // exit();
            }else
            {
                //get bank or cash id
                $cash_bank_id = \DB::table('transaction_chart_accounts')->select('source')->where('reference',$request->trans_id)->where('type','keluar')->where('memo',$request->trans_memo)->value('source');
                //get amount trans
                $amount_first = \DB::table('transaction_chart_accounts')->select('amount')->where('reference',$request->trans_id)->where('type','keluar')->where('memo',$request->trans_memo)->value('amount');
                $value_first = \DB::table('cashs')->select('value')->where('id',$cash_bank_id)->value('value');
                $back_value = $value_first+$amount_first;
                \DB::table('cashs')->where('id',$cash_bank_id)->update(['value'=>$back_value]);
                //print_r($back_value);
                //exit();
            }
        }else
        {
            if($request->trans_chart_account_id == 62)
            {
                //get bank or cash id
                $cash_bank_id = \DB::table('transaction_chart_accounts')->select('source')->where('reference',$request->trans_id)->where('type','masuk')->where('memo',$request->trans_memo)->value('source');
                //get amount trans
                $amount_first = \DB::table('transaction_chart_accounts')->select('amount')->where('reference',$request->trans_id)->where('type','masuk')->where('memo',$request->trans_memo)->value('amount');
                $value_first = \DB::table('banks')->select('value')->where('id',$cash_bank_id)->value('value');
                $back_value = $value_first-$amount_first;
                \DB::table('banks')->where('id',$cash_bank_id)->update(['value'=>$back_value]);
            }else
            {
                //get bank or cash id
                $cash_bank_id = \DB::table('transaction_chart_accounts')->select('source')->where('reference',$request->trans_id)->where('type','keluar')->where('memo',$request->trans_memo)->value('source');
                //get amount trans
                $amount_first = \DB::table('transaction_chart_accounts')->select('amount')->where('reference',$request->trans_id)->where('type','keluar')->where('memo',$request->trans_memo)->value('amount');
                $value_first = \DB::table('banks')->select('value')->where('id',$cash_bank_id)->value('value');
                $back_value = $value_first+$amount_first;
                \DB::table('banks')->where('id',$cash_bank_id)->update(['value'=>$back_value]);
            }
        }

        if($request->trans_chart_account_id == 62)
        {
            \DB::table('transaction_chart_accounts')->where('reference',$request->trans_id)->where('type','masuk')->where('memo',$request->trans_memo)->delete();
        }else
        {
            \DB::table('transaction_chart_accounts')->where('reference',$request->trans_id)->where('type','keluar')->where('memo',$request->trans_memo)->delete();
        }


        return redirect('biaya-operasi')
            ->with('successMessage','Jurnal Umum has been deleted');
    }
}
