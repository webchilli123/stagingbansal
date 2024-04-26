<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;


class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

   
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::with('role')->select();
  
            return DataTables::of($users)
                  ->addColumn('action', function ($user) {
                      return view('users.buttons')->with(['user' => $user]);
                  })->make(true);
        }
        return view('users.index');

    }

    public function create()
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|alpha_dash|unique:users',
            'password' => 'bail|required|confirmed|min:5',
            'role_id' => 'required',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User Created');
    }

    public function show()
    {
        return abort(404);
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|alpha_dash|'.Rule::unique('users')->ignore($user),
            'role_id' => 'required',
            'password' => 'nullable|confirmed|min:5',
        ]);


        $data = [
            'username' => $request->username,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $user->update($data);
        } else {
            $user->update($data);
        }

        return redirect()->route('users.index')->with('success', 'User Updated');
    }

    public function destroy(User $user)
    {   
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User Deleted');
    }
}
