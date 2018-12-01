<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Requests\StoreInvoiceTermRequest;
use App\Http\Requests\UpdateInvoiceTermRequest;

use App\InvoiceTerm;

class InvoiceTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('invoice-term-module'))
        {
            return view('invoice_term.index');
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
        if(\Auth::user()->can('create-invoice-term-module'))
        {
            return view('invoice_term.create');
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
    public function store(StoreInvoiceTermRequest $request)
    {
        $invoice_term = new InvoiceTerm;
        $invoice_term->name = $request->name;
        $invoice_term->day_many = $request->day_many;
        $invoice_term->created_at = date('Y-m-d h:i:s');
        $invoice_term->updated_at = date('Y-m-d h:i:s');
        $invoice_term->save();
        return redirect('invoice-term')
            ->with('successMessage', "Invoice Term has been added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice_term = InvoiceTerm::findOrFail($id);
        return view('invoice_term.show')
          ->with('invoice_term',$invoice_term);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-invoice-term-module'))
        {
            $invoice_term = InvoiceTerm::findOrFail($id);
            return view('invoice_term.edit')
                ->with('invoice_term', $invoice_term);
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
    public function update(UpdateInvoiceTermRequest $request, $id)
    {
        $invoice_term = InvoiceTerm::findOrFail($id);
        $invoice_term->name = $request->name;
        $invoice_term->day_many = $request->day_many;
        $invoice_term->save();
        return redirect('invoice-term/'.$id.'/edit')
            ->with('successMessage', "Invoice term has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $inv_term = InvoiceTerm::findOrFail($request->invoice_term_id);
        $inv_term->delete();

        return redirect('invoice-term')
          ->with('successMessage',"Invoice term has been deleted");
    }
}
