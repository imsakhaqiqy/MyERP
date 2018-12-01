<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;

use App\Unit;

class UnitController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('unit-module'))
        {
            return view('unit.index');
        }else{
            return view('403');
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create-unit-module'))
        {
            return view('unit.create');
        }else{
            return view('403');
        }
    }

    public function store(StoreUnitRequest $request)
    {
    	$unit = new Unit;
    	$unit->name = $request->name;
    	$unit->save();
    	return redirect('unit')
        ->with('successMessage','Unit has been deleted');
    }

    public function show($id)
    {
      $unit = Unit::findOrFail($id);
      return view('unit.show')
        ->with('unit',$unit);
    }

    public function destroy(Request $request)
    {
        $unit = Unit::findOrFail($request->unit_id);
        //count products
        $unit->delete();
	        return redirect('unit')
	            ->with('successMessage', "$unit->name has been deleted");
    }

    public function edit($id)
    {
        if(\Auth::user()->can('edit-unit-module'))
        {
            $unit = Unit::findOrFail($id);
        	return view('unit.edit')
        		->with('unit', $unit);
        }else{
            return view('403');
        }
    }

    public function update(UpdateUnitRequest $request, $id)
    {
    	$unit = Unit::findOrFail($id);
    	$unit->name = $request->name;
    	$unit->save();
    	return redirect('unit')
    		->with('successMessage', "Unit has been updated");
    }
}
