<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\User;
use App\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('user-list-module'))
      {
          return view('user.index');
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
      if(\Auth::user()->can('create-user-list-module'))
      {
        $role_options = Role::lists('name', 'id');
        return view('user.create')
            ->with('role_options', $role_options);
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
    public function store(StoreUserRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->created_at = date('Y-m-d h:i:s');
        $user->save();

        $user_id = $user->id;
        //attach role for this user
        $saved_user = User::find($user_id);
        $saved_user->roles()->attach($request->role_id);
        return redirect('user')
            ->with('successMessage', "User $saved_user->name has been added");


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.show')
            ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if(\Auth::user()->can('edit-user-list-module'))
      {
        $user = User::findOrFail($id);
        $role_options = Role::lists('name', 'id');
        return view('user.edit')
            ->with('user', $user)
            ->with('role_options', $role_options);
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->created_at = date('Y-m-d h:i:s');
        $user->save();

        //update the role
        $user->roles()->detach();
        $user->roles()->attach($request->role_id);
        return redirect('user/'.$id.'/edit')
            ->with('successMessage', "User has been updated");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->delete();

        return redirect('user')
          ->with('successMessage',"User $user->name has been deleted");
    }
}
