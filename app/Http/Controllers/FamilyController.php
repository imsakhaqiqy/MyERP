<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Family;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('family-module'))
        {
            $family = Family::all();
            return view('family.index')
                ->with('family',$family);
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
        if(\Auth::user()->can('create-family-module'))
        {
            $family = Family::all();
            $code_fix = '';
            if(count($family) > 0)
            {
                $code_family = Family::all()->max('id');
                $sub_str = $code_family+1;
                $code_fix = 'FM0'.$sub_str;
            }else
            {
                $code_family = count($family)+1;
                $code_fix = 'FM0'.$code_family;
            }
            return view('family.create')
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
    public function store(StoreFamilyRequest $request)
    {
        $family = new Family;
        $family->code = $request->code;
        $family->name = $request->name;
        $family->save();
        return redirect('family')
            ->with('successMessage','Family has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $family = Family::findOrFail($id);
        return view('family.show')
            ->with('family',$family);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-family-module'))
        {
            $family = Family::findOrFail($id);
            return view('family.edit')
                ->with('family',$family);
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
    public function update(UpdateFamilyRequest $request, $id)
    {
        $family = Family::findOrFail($id);
        $family->code = $request->code;
        $family->name = $request->name;
        $family->save();
        return redirect('family/'.$id.'/edit')
            ->with('successMessage','Family has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $family = Family::findOrFail($request->family_id);
        $family->delete();
        return redirect('family')
            ->with('successMessage','Family has been deleted');
    }
}
