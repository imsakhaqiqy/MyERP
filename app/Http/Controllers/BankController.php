<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;

use App\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('bank-module'))
        {
            return view('bank.index');
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
        if(\Auth::user()->can('create-bank-module'))
        {
            $bank = Bank::all();
            $code_fix = '';
            if(count($bank) > 0)
            {
                $code_bank = Bank::all()->max('id');
                $sub_str = $code_bank+1;
                $code_fix = 'BN0'.$sub_str;
            }else
            {
                $code_bank = count($bank)+1;
                $code_fix = 'BN0'.$code_bank;
            }
            return view('bank.create')
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
    public function store(StoreBankRequest $request)
    {
        $data = [
            'code'=>$request->code,
            'name'=>$request->name,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'value'=>floatval(preg_replace('#[^0-9.]#', '', $request->value))
        ];
        $save = Bank::create($data);
        return redirect('bank')
            ->with('successMessage', "Bank has been added");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return view('bank.show')->with('bank', $bank);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-bank-module'))
        {
            $bank = Bank::findOrFail($id);
            return view('bank.edit')
                ->with('bank', $bank);
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
    public function update(UpdateBankRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->code = $request->code;
        $bank->name = $request->name;
        $bank->account_name = $request->account_name;
        $bank->account_number = $request->account_number;
        $bank->value = floatval(preg_replace('#[^0-9.]#', '', $request->value));
        $bank->save();
        return redirect('bank/'.$id.'/edit')->with('successMessage', 'Bank has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $bank = Bank::findOrFail($request->bank_id);
        $bank->delete();
        return redirect('bank')
            ->with('successMessage', 'bank has been deleted');
    }
}
