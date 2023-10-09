<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::latest('id')->paginate(5);
        return response()->json($roles);
    }
    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return response()->json($permissions);
    }


    public function store(StoreRoleRequest $request)
    {
        $dataCreate = $request->all();
        $dataCreate['guard_name'] = 'web';
        $role = Role::create($dataCreate);
        $role->permissions()->attach($dataCreate['permission_ids']);
        return response()->json([
            $role,
            'message' => 'Create successed'
        ]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json(
            $role,
        );
    }
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        return response()->json([
            'roles' => $role,
            'permission' => $permissions
        ]);
    }

    public function update(StoreRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $dataUpdate = $request->all();
        $role->update($dataUpdate);
        $role->permissions()->sync($dataUpdate['permission_ids']);
        return response()->json([
            'message' => "Update successed"
        ]);
    }
    public function destroy($id)
    {
        Role::destroy($id);
        return response()->json(['message' => 'Delete successed']);
    }
}
