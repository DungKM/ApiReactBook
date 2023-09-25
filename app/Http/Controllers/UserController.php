<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function index(Request $request)
    {
        // $search = $request->get(key: 'q');
        $users = $this->user->latest('id')->paginate(3);
        return response()->json($users);
    }
    public function create()
    {
        $roles = $this->role->all()->groupBy('group');
        return response()->json($roles);
    }
    public function show($id)
    {
        $user = $this->user->findOrFail($id)->load('roles');
        return response()->json(
             $user,
        );
    }
    public function store(Request $request)
    {
        $dataCreate = $request->all();
        $dataCreate['password'] = Hash::make($request->password);
        $user = $this->user->create($dataCreate);
        $user->roles()->attach($dataCreate['role_ids']);
        return response()->json([
            $user,
            'message' => 'Create successed'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataUpdate = $request->except('password');
        $user = $this->user->findOrFail($id)->load('roles');
        if ($request->password) {

            $dataUpdate['password'] = Hash::make($request->password);
        }
        $user->update($dataUpdate);
        $user->roles()->sync($dataUpdate['role_ids'] ?? []);
        return response()->json([
            $user,
            'message' => 'Update successed'
        ]);
    }
    public function destroy(string $id)
    {
        $user = $this->user->findOrFail($id)->load('roles');
        $user->delete();
        return response()->json([
            $user,
            'message' => 'Delete successed'
        ]);
    }
}