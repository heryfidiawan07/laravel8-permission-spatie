<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=>'setSuperAdmin']);
    }

    public function index(UserDataTable $dataTable)
    {
        if(! auth()->user()->can('user-menu')) {
            abort(403);
        }

        return $dataTable->render('user.index', [
            'users' => User::get(),
            'roles' => Role::get(),
        ]);
    }

    public function store(UserRequest $request)
    {
        if(! auth()->user()->can('create-user')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $valid = $request->validated();
            $valid['password'] = Hash::make($valid['password']);
        	unset($valid['roles']);
            
            $user = User::create($valid);
            $user->assignRole(request('roles'));

            DB::commit();
            return ['status' => true, 'message' => "User created"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }

    public function update(UserRequest $request, $id)
    {
        if(! auth()->user()->can('edit-user')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $valid = $request->validated();
            unset($valid['roles']);
            if (request('password')) {
                $valid['password'] = Hash::make(request('password'));
            }

            $user = User::findOrFail($id);
            $user->update($valid);
            $user->syncRoles(request('roles'));

            DB::commit();
            return ['status' => true, 'message' => "User updated"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return [
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ];
    }

    public function destroy($id)
    {
        if(! auth()->user()->can('delete-user')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->syncRoles([]);
            $user->delete();

            DB::commit();
            return ['status' => true, 'message' => "User deleted"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }

    public function setSuperAdmin()
    {
        DB::beginTransaction();
        try {
            $permissions = \Spatie\Permission\Models\Permission::get();
            if(!$permissions->count()) {
                $data = [
                    ['name'=>'user-menu','guard_name'=>'web'],
                    ['name'=>'role-menu','guard_name'=>'web'],
                    ['name'=>'create-user','guard_name'=>'web'],
                    ['name'=>'edit-user','guard_name'=>'web'],
                    ['name'=>'delete-user','guard_name'=>'web'],
                    ['name'=>'create-role','guard_name'=>'web'],
                    ['name'=>'edit-role','guard_name'=>'web'],
                    ['name'=>'delete-role','guard_name'=>'web'],
                ];
                foreach($data as $key => $row) {
                    if($key > 1 && strpos($row['name'], 'user') !== false) {
                        $row['parent_id'] = \Spatie\Permission\Models\Permission::first()->id;
                    }elseif($key > 1 && strpos($row['name'], 'role') !== false) {
                        $row['parent_id'] = \Spatie\Permission\Models\Permission::skip(1)->first()->id;
                    }
                    \Spatie\Permission\Models\Permission::create($row);
                }
                $permissions = \Spatie\Permission\Models\Permission::get();
            }

            $role = Role::whereName('Super Admin')->first();
            if(!$role) {
                $role = Role::create(['name'=>'Super Admin','guard_name'=>'web']);
            }
            $role->givePermissionTo($permissions);

            $user = User::first();
            if(!$user) {
                $user = User::create(['name'=>'first','email'=>'first@mail.com','password'=>Hash::make('12345678')]);
            }
            if($user->roles->count() > 0) {
                $user->syncRoles($role);
            }else {
                $user->assignRole($role);
            }

            DB::commit();
            return ['status' => true, 'message' => "The Super Admin Assigned"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => false, 'message' => $th->getMessage()];
        }
    }
}
