<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreCashRequest;
use App\Http\Requests\UpdateCashRequest;

use App\Cash;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('cash-module'))
        {
            return view('cash.index');
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
        if(\Auth::user()->can('create-cash-module'))
        {
            $cash = Cash::all();
            $code_fix = '';
            if(count($cash) > 0)
            {
                $code_cash = Cash::all()->max('id');
                $sub_str = $code_cash+1;
                $code_fix = 'CAS0'.$sub_str;
            }else
            {
                $code_cash = count($cash)+1;
                $code_fix = 'CAS0'.$code_cash;
            }
            return view('cash.create')
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
    public function store(StoreCashRequest $request)
    {
        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'value' => floatval(preg_replace('#[^0-9.]#','',$request->value))
        ];
        $save = Cash::create($data);
        return redirect('cash')
            ->with('successMessage',"Cash has been added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cash = Cash::findOrFail($id);
        return view('cash.show')->with('cash',$cash);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-cash-module'))
        {
            $cash = Cash::findOrFail($id);
            return view('cash.edit')
                ->with('cash',$cash);
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
    public function update(UpdateCashRequest $request, $id)
    {
        $cash = Cash::findOrFail($id);
        $cash->code = $request->code;
        $cash->name = $request->name;
        $cash->value = floatval(preg_replace('#[^0-9.]#','',$request->value));
        $cash->save();
        return redirect('cash/'.$id.'/edit')->with('successMessage','Cash has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cash = Cash::findOrFail($request->cash_id);
        $cash->delete();
        return redirect('cash')
            ->with('successMessage','Cash has been deleted');
    }
}
