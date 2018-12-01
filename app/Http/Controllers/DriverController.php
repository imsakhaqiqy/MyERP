<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\Helper;

//Form requests
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;

use App\Driver;
class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('driver-module'))
        {
            return view('driver.index');
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
        if(\Auth::user()->can('create-driver-module'))
        {
            $driver = Driver::all();
            $code_fix = '';
            if(count($driver) > 0)
            {
                $code_driver = Driver::all()->max('id');
                $sub_str = $code_driver+1;
                $code_fix = 'DRV-'.$sub_str;
            }else
            {
                $code_driver = count($driver)+1;
                $code_fix = 'DRV-'.$code_driver;
            }
            return view('driver.create')
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
    public function store(StoreDriverRequest $request)
    {
        //
        $driver = new Driver;
        $driver->code = $request->code;
        $driver->name = Helper::capital($request->name);
        //preg_replace('/\s+/',' ',$driver->name);
        //echo ucwords($driver->name);
        $driver->contact_number = $request->primary_phone_number;
        $driver->save();
        return redirect('driver')
            ->with('successMessage','Driver has been added');
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
        $driver = Driver::findOrFail($id);
        return view('driver.show')
            ->with('driver', $driver);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-driver-module'))
        {
            $driver = Driver::findOrFail($id);
            return view('driver.edit')
                ->with('driver', $driver);
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
    public function update(UpdateDriverRequest $request, $id)
    {
        //
        $driver = Driver::findOrFail($id);
        $driver->code = $request->code;
        $driver->name = Helper::capital($request->name);
        //Helper::capital($driver->name);
        //$driver->name = Helper::sortir($driver->name);
        $driver->contact_number = preg_replace('/\s+/','',$request->contact_number);
        $driver->save();
        return redirect('driver')
            ->with('successMessage', "$driver->code has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $driver = Driver::findOrFail($request->driver_id);
        $driver->delete();
        return redirect('driver')
            ->with('successMessage',"$driver->code has been deleted");
    }
}
