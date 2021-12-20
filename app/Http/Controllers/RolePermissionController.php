<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(RoleDataTable $dataTable)
    {
        if(! auth()->user()->can('role-menu')) {
            abort(403);
        }

        return $dataTable->render('role-permission.index', [
            'roles' => Role::get(),
            'permissions' => Permission::has('children')->get(),
        ]);
    }

    public function store(RoleRequest $request)
    {
        // Super Admin allways tru because setup on AuthServiceProvider
        // dd(auth()->user()->can('create-role'));
        if(! auth()->user()->can('create-role')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $valid = $request->validated();
            unset($valid['permissions']);
            $valid['guard_name'] = request('guard_name') ?? 'web';
            
            $role = Role::create($valid);
            $role->givePermissionTo(request('permissions'));

            DB::commit();
            return ['status' => true, 'message' => "Role & Permission created"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }

    public function update(RoleRequest $request, $id)
    {
        if(! auth()->user()->can('edit-role')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $valid = $request->validated();
            unset($valid['permissions']);
            $valid['guard_name'] = request('guard_name') ?? 'web';

            $role = Role::findOrFail($id);
            $role->update($valid);
            $role->syncPermissions(request('permissions'));

            DB::commit();
            return ['status' => true, 'message' => "Role & Permission updated"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return [
            'role' => $role,
            'permissions' => $role->getPermissionNames(),
        ];
    }

    public function destroy($id)
    {
        if(! auth()->user()->can('delete-role')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->syncPermissions([]);
            $role->delete();

            DB::commit();
            return ['status' => true, 'message' => "Role & Permission deleted"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }
}
