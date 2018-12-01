<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('role-module'))
      {
        return view('role.index');
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
        if(\Auth::user()->can('create-role-module'))
        {
            return view('role.create');
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
    public function store(Request $request)
    {
        $role = New Role;
        $role->code = $request->code;
        $role->name = $request->name;
        $role->label = $request->description;
        $role->save();
        return redirect('role')
            ->with('successMessage','Role has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if(\Auth::user()->can('edit-role-module')){
        $role = Role::findORFail($id);
        $permissions = Permission::all();
        return view('role.show')
            ->with('role', $role)
            ->with('permissions', $permissions);
      }else{
        return view('403');
      }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-role-module'))
        {
            $role = Role::findORFail($id);
            return view('role.edit')
                ->with('role',$role);
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
        $role = Role::findORFail($id);
        $role->code = $request->code;
        $role->name = $request->name;
        $role->label = $request->label;
        $role->save();
        return redirect('role/'.$id.'/edit')
            ->with('successMessage','Role has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = Role::findORFail($request->role_id);
        $role->delete();
        return redirect('role')
            ->with('successMessage','Role has been deleted');
    }


    public function updateRolePermission(Request $request)
    {
        $role_id = $request->role_id;
        $role = Role::find($role_id);
        $role->permissions()->detach();
        $role->permissions()->attach($request->permission_id);
        return redirect('role/'.$role_id);
    }
}
