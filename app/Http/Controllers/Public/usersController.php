<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Public\Capitanias;
use App\Models\Public\Gerencias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;


class usersController extends Controller
{
    public static function middleware(): array
    {
        return [
            'ver-usuario|crear-usuario|editar-usuario|borrar-usuario' => ['only' => ['index']],
            'crear-usuario' => ['only' => ['create', 'store']],
            'editar-usuario' => ['only' => ['edit', 'update']],
            'borrar-usuario' => ['only' => ['destroy']],
        ];
    }

    public function index()
    {
        $users = User::select('users.*', 'capitanias.nombre as capitania')
            ->join('gerencias', 'gerencias.id', '=', 'users.gerencias_id as gerencia')
            ->leftjoin('capitanias', 'capitanias.id', '=', 'users.capitanias_id')
            ->get();

        return view('public.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $gerencias = Gerencias::pluck('nombre', 'id')->all();
        $capitanias = Capitanias::where('deleted_at', '=', null)->get();

        return view('public.users.create', compact('roles', 'gerencias', 'capitanias'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'capitanias_id' => 'required',
            'gerencias_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password|min:6|max:11|regex:/[A-Z]/|regex:/[0-9]/',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        // Convertir el valor de gerencias_id a una cadena de texto si es un array
        if (is_array($input['gerencias_id'])) {
            $input['gerencias_id'] = implode(',', $input['gerencias_id']);
        }

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('public.users.index');
    }

    public function edit($id)
    {
        $users = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $gerencias = Gerencias::pluck('nombre', 'id')->all();
        $capitanias = Capitanias::where('deleted_at', '=', null)->get();

        $usersRoles = $users->roles->pluck('name', 'name')->all();

        return view('public.users.edit', compact('usersRoles', 'roles', 'capitanias', 'gerencias'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|same:confirm-password|min:6|max:11|regex:/[A-Z]/|regex:/[0-9]/',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('public.users.index');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('public.users.index')->with('eliminar', 'ok');
    }
}
