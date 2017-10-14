<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Session;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the users
        $users = User::sortable()->paginate(10);

        // load the view and pass the users
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create')
            ->with('roles', UserRole::pluck('role_name','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6',
			'role_id' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('users/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user = new User;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = bcrypt(Input::get('password'));
            $user->role_id = Input::get('role_id');
            $user->save();

            // redirect
            Session::flash('message', 'Successfully created user!');
            return Redirect::to('users');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // show the view and pass the user to it
        return view('users.show')
            ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // show the edit form and pass the user
        return view('users.edit')->with('user', $user)
            ->with('roles', UserRole::pluck('role_name','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users,email,'.$user->id,
			'password' => 'required|min:6',
			'role_id' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('users/' . $user->id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = bcrypt(Input::get('password'));
			if($user->id != 1)
				$user->role_id = Input::get('role_id');
            $user->save();

            // redirect
            Session::flash('message', 'Successfully updated user!');
            return Redirect::to('users/'.$user->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
		if($user->id == 1)
		{
			Session::flash('message', 'Cannot delete user!');
			return Redirect::to('users');
		}
			
        // delete
        $user->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the user!');
        return Redirect::to('users');
    }
}
