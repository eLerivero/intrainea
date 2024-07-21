<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class rolesController extends Controller
{
    public static function middleware(): array
    {
        return [
            'ver-rol|crear-rol|editar-rol|borrar-rol' => ['only' => ['index']],
            'crear-rol' => ['only' => ['create', 'store']],
            'editar-rol' => ['only' => ['edit', 'update']],
            'borrar-rol' => ['only' => ['destroy']],
        ];
    }

    public function index()
    {
       $roles = Role::all();
       return view('public.roles.index' , compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::get();
        return view('public.roles.create' , compact('permissions'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('public.roles.index');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('public.roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('public.roles.index');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('public.roles.index');
    }
}
