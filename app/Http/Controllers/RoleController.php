<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest as storeRequest;
use App\Http\Requests\RoleUpdateRequest as updateRequest;
use App\Models\model_has_roles;
use App\Models\permission_role;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public $table = 'roles';

    public function __construct()
    {
        $this->getTh($this->table, 'Rol');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th'  => $this->th['index'],
            'data' => Role::whereNot('name', 'superAdmin')->orderBy('id', 'DESC')->paginate(15),
        ]);
    }
    public function list_permission(Request $r)
    {
        $role = Crypt::decryptString($r->role);
        return response()->json(['permission' => $this->getRolePermission($role)]);
    }



    public function apiSearch(Request $r)
    {
        return response()->json(
            [
                'list' => $this->getRolePermission(Crypt::decryptString($r->id))
            ]
        );
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
            'data'       => Role::whereNot('name', 'superAdmin')->where(DB::raw('UPPER(name)'), 'like', '%' . strtoupper($r->txtBusqueda) . '%')->paginate(),
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeRequest $r)
    {
        try {
            Role::create(['name' => $r->name]);
            return redirect()->back()->with('message', 'Se agrego un nuevo registro con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function store_permission(Request $r)
    {
        $messege = "";
        $type = true;
        try {
            $roleId = Crypt::decryptString($r->role);

            $role = Role::find($roleId);
            $permission = Permission::find($r->permission);

            if ($role->hasPermissionTo($permission->name)) {
                $type = $role->revokePermissionTo($permission);
            } else {
                $type = $role->givePermissionTo($permission);
            }
            $messege = $type ? "Accion realizada" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo" . $th->getMessage();
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "permission"    => $this->getRolePermission($roleId)
            ]
        );
    }

    private function getRolePermission($role)
    {
        return permission_role::with('permissions')->where('role_id', $role)->get();
    }
    private function setRolePermission($role, $permission)
    {
        try {

            $role = Role::find(Crypt::decryptString($role));
            $permission = Permission::find($permission);

            if ($role->hasPermissionTo($permission->name)) {
                $role->revokePermissionTo($permission);
            } else {
                $role->givePermissionTo($permission);
            }
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . ".show", [
                'th' => $this->th['show'],
                'p' => Role::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function usuarios($id)
    {
        try {
            return view($this->table . ".usuarios", [
                'th' => $this->th['show'],
                'p' => Role::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function getUsuariosApi(Request $r)
    {

        $role = Crypt::decryptString($r->role);
        return response()->json([
            'list' => $this->listUserRole($role)

        ]);
    }

    private function listUserRole($role)
    {
        return model_has_roles::with(['users'])
            ->where('role_id', $role)
            ->get();
    }

    public function setUsuariosApi(Request $r)
    {

        try {
            $message = 'Usuario ';
            $user = User::find(Crypt::decryptString($r->user_id));
            $role = Role::findById(Crypt::decryptString($r->role_id));
            if (model_has_roles::where('role_id', $role->id)->where('model_id', $user->id)->count() > 0) {
                $message = $message . "eliminado ";
                $user->removeRole($role->name);
            } else {
                $message =  $message . "agregado ";
                $user->assignRole($role->name);
            }
            return response()->json([
                'list' => $this->listUserRole($role->id),
                'message' => $message . "correctamente",
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'list' => $this->listUserRole(Crypt::decryptString($r->role_id)),
                'message' => "Error: " . $th->getMessage(),
                'type' => 'danger'
            ]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . ".edit", [
                'th' => $this->th['edit'],
                'p' => Role::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateRequest $request)
    {
        try {
            $p = Role::findOrFail($request->id);
            $p->name = $request->name;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro actualizado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
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
                'p' => Role::findOrFail(Crypt::decryptString($id))
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Role::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
