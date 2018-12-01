<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('vehicle-module'))
        {
            return view('vehicle.index');
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
        if(\Auth::user()->can('create-vehicle-module'))
        {
            $vehicle = Vehicle::all();
            $code_fix = '';
            if(count($vehicle) > 0)
            {
                $code_vehicle = Vehicle::all()->max('id');
                $sub_str = $code_vehicle+1;
                $code_fix = 'VHC0'.$sub_str;
            }else
            {
                $code_vehicle = count($vehicle)+1;
                $code_fix = 'VHC0'.$code_vehicle;
            }
            $vehicle_category = ['Truck','Pick Up','Motorcycle'];
            return view('vehicle.create')
                ->with('vehicle_cat',$vehicle_category)
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
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = new Vehicle;
        $vehicle->code = $request->code;
        $category = $request->vehicle_category;
        $update_category;
        if($category == 0){
            $update_category = "truck";
        }elseif ($category == 1) {
            $update_category = "pick_up";
        }elseif ($category == 2){
            $update_category = "motorcycle";
        }
        $vehicle->category = $update_category;
        $vehicle->number_of_vehicle = $request->number_of_vehicle;
        $vehicle->save();
        return redirect('vehicle')
            ->with('successMessage','Vehicle has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.show')
            ->with('vehicle',$vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-vehicle-module'))
        {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle_category = ['truck'=>'Truck','pick_up'=>'Pick Up','motorcycle'=>'Motorcycle'];
            return view('vehicle.edit')
                ->with('vehicle',$vehicle)
                ->with('vehicle_cat',$vehicle_category);
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
    public function update(UpdateVehicleRequest $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->code = $request->code;
        $vehicle->category = $request->category;
        $vehicle->number_of_vehicle = $request->number_of_vehicle;
        $vehicle->save();
        return redirect('vehicle')
            ->with('successMessage', "$vehicle->code has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $vehicle->delete();
        return redirect('vehicle')
            ->with('successMessage',"$vehicle->code has been deleted");
    }
}
