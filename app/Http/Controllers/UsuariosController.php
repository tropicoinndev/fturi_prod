<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\cajas;
use App\Models\cajas_users;
use App\Models\empleados;
use App\Models\Enums\user_token;
use App\Models\tipo_mantenimiento_users;
use App\Models\tipo_mantenimientos;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    public $table = 'users';

    public $token;

    public function __construct()
    {
        $this->getTh($this->table, 'Usuarios');
    }
    /**aqui se muestar el form para cambiar contrasena*/
    public function showPasswordForm()
    {
        return view('users.change-password-form');
    }
    /**esta funcion cambian la contrasena de ul usuario logueado */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|different:current_password',
                'confirm_password' => 'required|same:new_password',
            ]);

            $user = Auth::user();

            if (Hash::check($request->current_password, $user->password)) {
                $usuario = User::find($user->id);
                $usuario->password = Hash::make($request->new_password);
                $usuario->save();
                //$user->update(['password' => Hash::make($request->new_password)]);
                Auth::logout();
                return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente. Por favor, inicie sesión nuevamente.');
            } else {
                return redirect()->back()->with('error', 'La contraseña actual es incorrecta.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error: acción no realizada, actualice e intente de nuevo.');
        }
    }
    /**api para listar usuarios */
    public function api_usuario()
    {
        return response()->json(['list' => $this->getList()]);
    }

    private function getList()
    {
        return User::orderBy('name', 'ASC')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => User::whereNot('name', 'admin')->orderBy('id', 'DESC')->paginate(15),
            'token' => user_token::getAll(),
            'table' => $this->table,

        ]);
    }
    public function menu()
    {
        $users = User::paginate(5);
        $empleados = empleados::paginate(10);
        $roles = Role::paginate(10);

        return view('users.menu', compact(
            'users',
            'empleados',
            'roles'

        ));
    }
    /***funcione para gregar tipo de mantenimientos a usuarios */
    /***api listar tipo_mantenimientos disponibles */
    public function list_tipoMantenimientos(Request $r)
    {
        $user = Crypt::decryptString($r->user);
        return response()->json(['tipo_mantenimientos' => $this->getMantenimientoUsuario($user)]);
    }
    /**funcion que trae mantenimientoUsuario */
    private function getMantenimientoUsuario($user)
    {
        return tipo_mantenimiento_users::where('users_id', '=', $user)->with('tipo_mantenimientos')->get();
    }
    /** buscar tipo_mantenimientos*/
    public function apiSearchTipoMantenimiento(Request $r)
    {
        return response()->json(
            [
                'listm' => $this->getMantenimientoUsuario(Crypt::decryptString($r->id))
            ]
        );
    }
    /**esta funcion es donde se agrega el tipo de mantenimiento a usuario  */
    public function store_apiTipoMantenimientos(Request $r)
    {

        $messege = "";
        $type = true;
        try {
            $user = Crypt::decryptString($r->user);
            $type = $this->setTipoMantenimientosUsuario($user, $r->tipo_mantenimiento);
            $messege = $type ? "tipo de mantenimiento agregado a este usuario" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo";
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "tipo_mantenimientos"    => $this->getMantenimientoUsuario($user)
            ]
        );
    }
    /**esta funcion guarda la caja creada para el usuario */
    private function setTipoMantenimientosUsuario($user, $tipo_mantenimiento)
    {

        try {
            $inUserTipom = tipo_mantenimiento_users::where('users_id', $user)->where('tipo_mantenimientos_id', $tipo_mantenimiento)->count();

            if ($inUserTipom >= 1) {
                tipo_mantenimiento_users::where('users_id', $user)->where('tipo_mantenimientos_id', $tipo_mantenimiento)->delete();
            } else {
                $p = new tipo_mantenimiento_users;
                $p->users_id = $user;
                $p->tipo_mantenimientos_id = $tipo_mantenimiento;
                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**aqui finalizan las funciones de agregar tipo_mantenimientos a usuarios */


    /***api listar cajas disponibles */
    public function list_cajas(Request $r)
    {
        $user = Crypt::decryptString($r->user);
        return response()->json(['cajas' => $this->getCajaUsuario($user)]);
    }
    /**funcion que trae cajaUsuario */
    private function getCajaUsuario($user)
    {
        return cajas_users::where('users_id', '=', $user)->with('cajas')->get();
    }

    /** buscar caja */
    public function apiSearchCaja(Request $r)
    {
        return response()->json(
            [
                'list' => $this->getCajaUsuario(Crypt::decryptString($r->id))
            ]
        );
    }

    /**esta funcion es donde se agrega la caja a usuario  */
    public function store_apiCaja(Request $r)
    {

        $messege = "";
        $type = true;
        try {
            $user = Crypt::decryptString($r->user);
            $type = $this->setCajasUsuario($user, $r->caja);
            $messege = $type ? "Caja agregada a este usuario" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo";
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "cajas"    => $this->getCajaUsuario($user)
            ]
        );
    }
    /**esta funcion guarda la caja creada para el usuario */
    private function setCajasUsuario($user, $caja)
    {

        try {
            $inUser = cajas_users::where('users_id', $user)->where('cajas_id', $caja)->count();

            if ($inUser >= 1) {
                cajas_users::where('users_id', $user)->where('cajas_id', $caja)->delete();
            } else {
                $p = new cajas_users;
                $p->users_id = $user;
                $p->cajas_id = $caja;
                $p->pin = Hash::make('1234');
                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**funcion para asignar roles aun usuario */
    public function asignarRol(Request $r)
    {
        try {
            $roles = $r->roles_id;
            $user = User::find(Crypt::decryptString($r->usuario));
            $user->assignRole($roles);
            if ($user->hasRole($roles)) {
                return redirect()->back()
                    ->with('message', 'Este usuario ya tiene habilitado el rol:  ' . $roles)
                    ->with('type', 'info');
            } else {
                return redirect()->back()
                    ->with('message', 'Se asigno rol a este usuario:   '  . $roles)
                    ->with('type', 'success');
            }
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**funcion para desasignar roles aun usuario */
    public function desasignar_Rol(Request $r)
    {
        try {
            $user = User::has('roles')->find(Crypt::decryptString($r->usuario));

            $roles = $r->rolName;

            $user->removeRole($roles);

            return redirect()->back()
                ->with('message', 'Este usuario ya se le desahabilito este rol:    '  . $roles)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $r)
    {

        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => User::whereNot('name', 'admin')
                ->where(function ($q) use ($r) {
                    $q->where(DB::raw('UPPER(name)'), 'like', '%' . strtoupper($r->txtBusqueda) . '%')
                        ->orWhere(DB::raw('LOWER(email)'), 'like', '%' . strtolower($r->txtBusqueda) . '%');
                })
                ->paginate(),
                'token' => user_token::getAll(),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,

        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        try {
            $p = new User;
            $p->name = $request->name;
            $p->email = $request->email;
            $p->password = Hash::make('12345678');
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->name)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::with(['roles'])->findOrFail(Crypt::decryptString($id));


        return view($this->table . '.show', [
            'th' => $this->th['show'] =  [
                'title'     => $user->name,
                'sub'       => 'Detalles ',
                'table'     => $this->table,
                'bread'     => $this->table . '.show'
            ],
            'p' => $user,
            'table' => $this->table,
            'roles' => Role::get(),
            'usuarios' => User::where('id', $user->id)->get(),
            'rolesU' => $user->roles,
            'cajas' => cajas::orderBy('caja', 'ASC')->get(),
            'tipoM' => tipo_mantenimientos::orderBy('mantenimiento', 'ASC')->get(),
            'cajasU' => cajas_users::where('users_id', '=',  $user->id)->get(),
            'mantenimientosUsuarios' => tipo_mantenimiento_users::where('users_id', '=',  $user->id)->get(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *@param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            return view($this->table . ".edit", [
                'th' => $this->th['edit'],
                'p' => User::findOrFail(Crypt::decryptString($id)),

            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *@param int $id
     * @param  \App\Http\Requests\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(updateUsersRequest $request)
    {
        try {

            $p = User::findOrFail($request->id);
            $p->name = $request->name;
            $p->email = $request->email;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->name)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => User::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            User::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function destroy_api(Request $r)
    {
        $m = "Se elimino un usuario";
        $t = true;
        try {
            User::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " . $th->getMessage();
        }
        return response()->json([
            'list' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
    public function restablecerPassword($id)
    {
        try {
            $userId = Crypt::decryptString($id);
            $user = User::with(['roles'])->findOrFail($userId);
            $user->password = Hash::make('12345678');
            $user->save();
            return redirect()->route($this->table . '.index')
                ->with('message', 'Se restablecio su contraseña a la por defecto');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function setToken(Request $r)
    {
        try {
            $user = User::find(Crypt::decryptString($r->id));
            $user->token = $r->value;
            $user->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }
}
